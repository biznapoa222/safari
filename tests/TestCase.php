<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function signInAsAdministrator(): User
    {
        $user = User::query()->where('role', 'administrator')->firstOrFail();
        $this->actingAs($user);

        return $user;
    }
}
