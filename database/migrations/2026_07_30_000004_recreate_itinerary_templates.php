<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::dropIfExists('template_day_activities');
        Schema::dropIfExists('template_pricing');
        Schema::dropIfExists('template_days');
        Schema::dropIfExists('proposal_template_settings');
        Schema::dropIfExists('itinerary_templates');
        Schema::dropIfExists('hotels');

        Schema::create('hotels', function (Blueprint $table) {
            $table->id(); $table->string('name');
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->integer('star_rating')->default(3);
            $table->string('tier')->nullable();
            $table->string('meal_plan')->nullable();
            $table->text('description')->nullable();
            $table->json('amenities')->nullable();
            $table->string('hero_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('website')->nullable();
            $table->string('gps')->nullable();
            $table->json('rates')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes(); $table->timestamps();
        });

        Schema::create('itinerary_templates', function (Blueprint $table) {
            $table->id(); $table->string('name');
            $table->string('trip_name')->nullable();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->integer('duration_days')->default(1);
            $table->string('category')->nullable();
            $table->text('overview')->nullable();
            $table->text('highlights')->nullable();
            $table->text('includes')->nullable();
            $table->text('excludes')->nullable();
            $table->text('terms')->nullable();
            $table->text('booking_terms')->nullable();
            $table->text('payment_schedule')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->text('refund_policy')->nullable();
            $table->text('important_notes')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('draft');
            $table->softDeletes(); $table->timestamps();
        });

        Schema::create('template_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('itinerary_template_id');
            $table->integer('day_number');
            $table->date('date')->nullable();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->string('hotel_name')->nullable();
            $table->string('room_type')->nullable();
            $table->string('meal_plan')->nullable();
            $table->text('morning_activity')->nullable();
            $table->text('afternoon_activity')->nullable();
            $table->text('evening_activity')->nullable();
            $table->text('description')->nullable();
            $table->text('destination_intro')->nullable();
            $table->string('image')->nullable();
            $table->text('wildlife_highlights')->nullable();
            $table->text('included_services')->nullable();
            $table->text('optional_activities')->nullable();
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index('itinerary_template_id');
        });

        Schema::create('template_pricing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('itinerary_template_id');
            $table->string('currency', 3)->default('USD');
            $table->decimal('price_per_person', 12, 2)->nullable();
            $table->decimal('single_supplement', 12, 2)->nullable();
            $table->decimal('total_cost', 14, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('template_day_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_day_id');
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->string('activity_name')->nullable();
            $table->text('description')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('is_optional')->default(false);
            $table->boolean('is_included')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('proposal_template_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('itinerary_template_id');
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->index('itinerary_template_id');
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        Schema::dropIfExists('template_day_activities');
        Schema::dropIfExists('template_pricing');
        Schema::dropIfExists('template_days');
        Schema::dropIfExists('proposal_template_settings');
        Schema::dropIfExists('itinerary_templates');
        Schema::dropIfExists('hotels');
    }
};
