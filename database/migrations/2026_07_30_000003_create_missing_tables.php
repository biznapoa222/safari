<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('proposal_template_settings')) {
            Schema::create('proposal_template_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('itinerary_template_id');
                $table->json('settings')->nullable();
                $table->timestamps();
                $table->index('itinerary_template_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_template_settings');
    }
};
