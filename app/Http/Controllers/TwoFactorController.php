<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function __construct(private TwoFactorService $twoFactor) {}

    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);
        $this->twoFactor->ensureColumns();
        $user = $request->user();
        $pendingSecret = $this->twoFactor->revealSecret($user->two_factor_pending_secret);

        return view('admin.two-factor.index', [
            'user' => $user,
            'enabled' => $user->twoFactorEnabled(),
            'pendingSecret' => $pendingSecret,
            'otpAuthUri' => $pendingSecret ? $this->twoFactor->otpAuthUri($user, $pendingSecret) : null,
            'qrCode' => $pendingSecret ? $this->twoFactor->qrCodeSource($this->twoFactor->otpAuthUri($user, $pendingSecret)) : null,
        ]);
    }

    public function start(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $this->twoFactor->ensureColumns();
        $secret = $this->twoFactor->generateSecret();
        $request->user()->update(['two_factor_pending_secret' => $this->twoFactor->protectSecret($secret)]);

        return back()->with('two_factor_started', 'Scan the QR code or enter the setup key, then confirm a 6-digit code.');
    }

    public function confirm(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $this->twoFactor->ensureColumns();
        $request->validate(['code' => ['required', 'digits:6']]);
        $user = $request->user()->fresh();

        if (!$this->twoFactor->verify($user->two_factor_pending_secret, $request->input('code'))) {
            return back()->withErrors(['code' => 'Invalid authenticator code.'])->withInput();
        }

        $user->update([
            'two_factor_secret' => $user->two_factor_pending_secret,
            'two_factor_pending_secret' => null,
            'two_factor_confirmed_at' => now(),
        ]);

        return redirect()->route('admin.two-factor.index')->with('success', 'Google Authenticator is now enabled.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $this->twoFactor->ensureColumns();
        $request->validate(['password' => ['required', 'string']]);

        if (!Hash::check($request->input('password'), $request->user()->password)) {
            return back()->withErrors(['password' => 'Current password is required.']);
        }

        $request->user()->update([
            'two_factor_secret' => null,
            'two_factor_pending_secret' => null,
            'two_factor_confirmed_at' => null,
        ]);

        return back()->with('success', 'Google Authenticator has been disabled.');
    }

    public function challenge(): View|RedirectResponse
    {
        $this->twoFactor->ensureColumns();
        $user = $this->pendingUser(request());
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Your authenticator session has expired.']);
        }

        return view('auth.two-factor-challenge', compact('user'));
    }

    public function verifyChallenge(Request $request): RedirectResponse
    {
        $this->twoFactor->ensureColumns();
        $request->validate(['code' => ['required', 'digits:6']]);
        $user = $this->pendingUser($request);

        if (!$user || !$this->twoFactor->verify($user->two_factor_secret, $request->input('code'))) {
            return back()->withErrors(['code' => 'Invalid authenticator code.'])->withInput();
        }

        Auth::login($user, (bool) $request->session()->pull('two_factor_remember', false));
        $request->session()->regenerate();
        $user->update(['last_login_at' => now()]);

        return redirect()->intended(route('admin.dashboard'));
    }

    private function pendingUser(Request $request): ?User
    {
        $id = (int) $request->session()->get('two_factor_pending_user_id');
        $user = $id ? User::find($id) : null;

        return $user && $user->is_active && $user->twoFactorEnabled() ? $user : null;
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'administrator', 403);
    }
}
