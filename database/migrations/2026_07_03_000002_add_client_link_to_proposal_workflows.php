<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_workflows', function (Blueprint $table) {
            $table->string('client_token', 64)->nullable()->unique()->after('proposal_type');
            $table->boolean('client_link_enabled')->default(true)->after('client_token');
            $table->timestamp('client_link_expires_at')->nullable()->after('client_link_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_workflows', function (Blueprint $table) {
            $table->dropUnique(['client_token']);
            $table->dropColumn(['client_token', 'client_link_enabled', 'client_link_expires_at']);
        });
    }
};
