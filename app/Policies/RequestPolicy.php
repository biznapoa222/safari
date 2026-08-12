<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;

class RequestPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->role === 'administrator') {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Request $request): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Request $request): bool
    {
        return in_array($user->role, ['administrator', 'manager', 'sales'])
            || $request->assigned_to === $user->id
            || $request->assigned_consultant_id === $user->id;
    }

    public function delete(User $user, Request $request): bool
    {
        return in_array($user->role, ['administrator', 'manager']);
    }

    public function restore(User $user, Request $request): bool
    {
        return $user->role === 'administrator';
    }

    public function forceDelete(User $user, Request $request): bool
    {
        return $user->role === 'administrator';
    }

    public function convertToQuote(User $user, Request $request): bool
    {
        return in_array($user->role, ['administrator', 'manager', 'sales']);
    }
}
