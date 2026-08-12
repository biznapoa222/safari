<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function store(Request $request, TwoFactorService $twoFactor): RedirectResponse
    {
        $twoFactor->ensureColumns();
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt([...$credentials, 'is_active' => true], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'The email, password or account status is not valid.',
            ]);
        }

        $request->session()->regenerate();
        $user = $request->user();
        if ($user->twoFactorEnabled()) {
            $request->session()->put('two_factor_pending_user_id', $user->id);
            $request->session()->put('two_factor_remember', $request->boolean('remember'));
            Auth::logout();

            return redirect()->route('two-factor.challenge');
        }

        $user->update(['last_login_at' => now()]);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have signed out securely.');
    }
}
