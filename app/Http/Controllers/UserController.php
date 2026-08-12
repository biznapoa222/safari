<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        $users = User::query()
            ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('department', 'like', '%'.$request->search.'%');
            }))
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->role))
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users.index', ['users' => $users, 'roles' => User::roles()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $data = $this->validateUser($request);
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active');
        User::query()->create($data);

        return back()->with('success', 'User account created.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $data = $this->validateUser($request, $user);
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);

        return back()->with('success', 'User account updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin($request);
        abort_if($request->user()->is($user), 422, 'You cannot delete your own signed-in account.');
        $user->delete();

        return back()->with('success', 'User account deleted.');
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180', Rule::unique('users')->ignore($user?->id)],
            'role' => ['required', Rule::in(array_keys(User::roles()))],
            'department' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'max:100'],
        ]);
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'administrator', 403);
    }
}
