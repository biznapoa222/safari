<?php

namespace App\Providers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Auto-audit all created/updated/deleted models
        Model::created(function (Model $model) {
            $this->log('create', $model);
        });

        Model::updated(function (Model $model) {
            $this->log('edit', $model);
        });

        Model::deleted(function (Model $model) {
            if (!in_array(class_basename($model), ['AuditLog', 'User'])) {
                $this->log('delete', $model);
            }
        });
    }

    private function log(string $action, Model $model): void
    {
        try {
            $skipModules = ['AuditLog', 'Session', 'Cache', 'Job', 'PersonalAccessToken'];
            if (in_array(class_basename($model), $skipModules)) return;

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'module' => class_basename($model),
                'description' => $action . ' ' . class_basename($model) . ' #' . $model->getKey(),
                'auditable_type' => get_class($model),
                'auditable_id' => $model->getKey(),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Silent fail for audit logging
        }
    }
}
