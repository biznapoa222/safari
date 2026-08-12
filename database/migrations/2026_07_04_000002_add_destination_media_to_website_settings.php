<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', fn (Blueprint $table) => $table->json('destination_media')->nullable());
    }

    public function down(): void
    {
        Schema::table('website_settings', fn (Blueprint $table) => $table->dropColumn('destination_media'));
    }
};
