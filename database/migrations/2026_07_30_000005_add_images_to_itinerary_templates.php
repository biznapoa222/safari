<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('itinerary_templates', 'images')) {
            return;
        }

        Schema::table('itinerary_templates', function (Blueprint $table) {
            $table->json('images')->nullable()->after('important_notes');
        });
    }

    public function down(): void
    {
        Schema::table('itinerary_templates', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
