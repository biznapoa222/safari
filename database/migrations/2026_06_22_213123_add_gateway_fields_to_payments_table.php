<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('gateway')->nullable()->after('method');
            $table->string('gateway_session_id')->nullable()->after('gateway');
            $table->json('gateway_response')->nullable()->after('gateway_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['gateway', 'gateway_session_id', 'gateway_response']);
        });
    }
};
