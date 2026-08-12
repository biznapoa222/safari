<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('destinations')) {
            Schema::create('destinations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('country')->nullable();
                $table->text('description')->nullable();
                $table->text('highlights')->nullable();
                $table->text('wildlife')->nullable();
                $table->string('climate')->nullable();
                $table->string('best_time_to_visit')->nullable();
                $table->string('hero_image')->nullable();
                $table->json('gallery')->nullable();
                $table->text('activities_list')->nullable();
                $table->boolean('status')->default(true);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('hotels')) {
            Schema::create('hotels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('destination_id')->nullable()->constrained('destinations')->nullOnDelete();
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
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('activities')) {
            Schema::create('activities', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('destination_id')->nullable()->constrained('destinations')->nullOnDelete();
                $table->text('description')->nullable();
                $table->string('duration')->nullable();
                $table->boolean('is_included')->default(true);
                $table->decimal('price', 10, 2)->nullable();
                $table->string('currency', 3)->default('USD');
                $table->string('image')->nullable();
                $table->boolean('status')->default(true);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('itinerary_templates')) {
            Schema::create('itinerary_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('trip_name')->nullable();
                $table->foreignId('destination_id')->nullable()->constrained('destinations')->nullOnDelete();
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
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('template_days')) {
            Schema::create('template_days', function (Blueprint $table) {
                $table->id();
                $table->foreignId('itinerary_template_id')->constrained()->cascadeOnDelete();
                $table->integer('day_number');
                $table->date('date')->nullable();
                $table->string('title')->nullable();
                $table->foreignId('destination_id')->nullable()->constrained('destinations')->nullOnDelete();
                $table->foreignId('hotel_id')->nullable()->constrained('hotels')->nullOnDelete();
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
        }

        Schema::create('template_day_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_day_id')->constrained()->cascadeOnDelete();
            $table->foreignId('activity_id')->nullable()->constrained('activities')->nullOnDelete();
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

        Schema::create('template_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_template_id')->constrained('itinerary_templates')->cascadeOnDelete();
            $table->string('currency', 3)->default('USD');
            $table->decimal('price_per_person', 12, 2)->nullable();
            $table->decimal('single_supplement', 12, 2)->nullable();
            $table->decimal('total_cost', 14, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('proposal_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('Shishi Footsteps');
            $table->string('logo')->nullable();
            $table->text('company_profile')->nullable();
            $table->text('awards')->nullable();
            $table->text('certifications')->nullable();
            $table->text('testimonials')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('website')->nullable();
            $table->json('social_links')->nullable();
            $table->text('booking_terms')->nullable();
            $table->text('payment_schedule')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->text('refund_policy')->nullable();
            $table->text('important_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_settings');
        Schema::dropIfExists('template_pricing');
        Schema::dropIfExists('template_day_activities');
        Schema::dropIfExists('template_days');
        Schema::dropIfExists('itinerary_templates');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('hotels');
        Schema::dropIfExists('destinations');
    }
};
