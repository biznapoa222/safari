<?php

namespace App\Providers;

use App\Models\Request;
use App\Policies\RequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Request::class => RequestPolicy::class,
    ];

    public function boot(): void {}
}
