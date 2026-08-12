<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('countries')->default('Kenya');
            $table->text('summary');
            $table->longText('description')->nullable();
            $table->unsignedSmallInteger('duration_days')->default(1);
            $table->unsignedSmallInteger('nights')->default(0);
            $table->unsignedSmallInteger('minimum_guests')->default(1);
            $table->unsignedSmallInteger('maximum_guests')->default(12);
            $table->decimal('price_from', 12, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('travel_style')->default('Private safari');
            $table->string('difficulty')->default('Easy');
            $table->string('start_location')->nullable();
            $table->string('end_location')->nullable();
            $table->string('best_time')->nullable();
            $table->string('accommodation_level')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('featured')->default(false);
            $table->string('cover_image')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->text('important_notes')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('itinerary_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('day_number');
            $table->string('title');
            $table->string('location')->nullable();
            $table->string('accommodation')->nullable();
            $table->string('meal_plan')->nullable();
            $table->unsignedInteger('distance_km')->nullable();
            $table->decimal('driving_hours', 5, 2)->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->json('activities')->nullable();
            $table->string('overnight')->nullable();
            $table->string('primary_image')->nullable();
            $table->timestamps();
            $table->unique(['itinerary_id', 'day_number']);
        });

        Schema::create('itinerary_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('itinerary_day_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('credit')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itinerary_images');
        Schema::dropIfExists('itinerary_days');
        Schema::dropIfExists('itineraries');
    }
};
