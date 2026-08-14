<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ensure itinerary template schema matches models/seeders without destroying hotels.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->ensureItineraryTemplatesSchema();
        $this->ensureTemplateDaysSchema();
        $this->ensureTemplatePricingSchema();
        $this->ensureTemplateDayActivitiesSchema();
        $this->ensureProposalTemplateSettingsSchema();
        $this->ensureHotelsCmsColumns();
    }

    public function down(): void
    {
        // Keep data; this migration only ensures columns/tables exist.
    }

    private function ensureItineraryTemplatesSchema(): void
    {
        if (! Schema::hasTable('itinerary_templates')) {
            Schema::create('itinerary_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
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
                $table->json('images')->nullable();
                $table->string('status')->default('draft');
                $table->softDeletes();
                $table->timestamps();
            });

            return;
        }

        Schema::table('itinerary_templates', function (Blueprint $table) {
            foreach ([
                'trip_name' => fn (Blueprint $t) => $t->string('trip_name')->nullable(),
                'destination_id' => fn (Blueprint $t) => $t->unsignedBigInteger('destination_id')->nullable(),
                'booking_terms' => fn (Blueprint $t) => $t->text('booking_terms')->nullable(),
                'payment_schedule' => fn (Blueprint $t) => $t->text('payment_schedule')->nullable(),
                'refund_policy' => fn (Blueprint $t) => $t->text('refund_policy')->nullable(),
                'important_notes' => fn (Blueprint $t) => $t->text('important_notes')->nullable(),
                'images' => fn (Blueprint $t) => $t->json('images')->nullable(),
            ] as $column => $definition) {
                if (! Schema::hasColumn('itinerary_templates', $column)) {
                    $definition($table);
                }
            }

            if (! Schema::hasColumn('itinerary_templates', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    private function ensureTemplateDaysSchema(): void
    {
        if (! Schema::hasTable('template_days')) {
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

            return;
        }

        Schema::table('template_days', function (Blueprint $table) {
            foreach ([
                'itinerary_template_id' => fn (Blueprint $t) => $t->unsignedBigInteger('itinerary_template_id')->nullable(),
                'date' => fn (Blueprint $t) => $t->date('date')->nullable(),
                'destination_id' => fn (Blueprint $t) => $t->unsignedBigInteger('destination_id')->nullable(),
                'hotel_id' => fn (Blueprint $t) => $t->unsignedBigInteger('hotel_id')->nullable(),
                'hotel_name' => fn (Blueprint $t) => $t->string('hotel_name')->nullable(),
                'room_type' => fn (Blueprint $t) => $t->string('room_type')->nullable(),
                'destination_intro' => fn (Blueprint $t) => $t->text('destination_intro')->nullable(),
                'image' => fn (Blueprint $t) => $t->string('image')->nullable(),
                'wildlife_highlights' => fn (Blueprint $t) => $t->text('wildlife_highlights')->nullable(),
                'included_services' => fn (Blueprint $t) => $t->text('included_services')->nullable(),
                'optional_activities' => fn (Blueprint $t) => $t->text('optional_activities')->nullable(),
                'sort_order' => fn (Blueprint $t) => $t->integer('sort_order')->default(0),
            ] as $column => $definition) {
                if (! Schema::hasColumn('template_days', $column)) {
                    $definition($table);
                }
            }
        });

        if (Schema::hasColumn('template_days', 'template_id') && Schema::hasColumn('template_days', 'itinerary_template_id')) {
            DB::table('template_days')
                ->whereNull('itinerary_template_id')
                ->update(['itinerary_template_id' => DB::raw('template_id')]);
        }
    }

    private function ensureTemplatePricingSchema(): void
    {
        if (Schema::hasTable('template_pricing')) {
            return;
        }

        Schema::create('template_pricing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('itinerary_template_id');
            $table->string('currency', 3)->default('USD');
            $table->decimal('price_per_person', 12, 2)->nullable();
            $table->decimal('single_supplement', 12, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('itinerary_template_id');
        });
    }

    private function ensureTemplateDayActivitiesSchema(): void
    {
        if (Schema::hasTable('template_day_activities')) {
            return;
        }

        Schema::create('template_day_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_day_id');
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->string('name')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->boolean('is_optional')->default(false);
            $table->boolean('is_included')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index('template_day_id');
        });
    }

    private function ensureProposalTemplateSettingsSchema(): void
    {
        if (Schema::hasTable('proposal_template_settings')) {
            return;
        }

        Schema::create('proposal_template_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('itinerary_template_id');
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->index('itinerary_template_id');
        });
    }

    private function ensureHotelsCmsColumns(): void
    {
        if (! Schema::hasTable('hotels')) {
            return;
        }

        Schema::table('hotels', function (Blueprint $table) {
            foreach ([
                'destination_id' => fn (Blueprint $t) => $t->unsignedBigInteger('destination_id')->nullable(),
                'star_rating' => fn (Blueprint $t) => $t->integer('star_rating')->nullable(),
                'tier' => fn (Blueprint $t) => $t->string('tier')->nullable(),
                'meal_plan' => fn (Blueprint $t) => $t->string('meal_plan')->nullable(),
                'description' => fn (Blueprint $t) => $t->text('description')->nullable(),
                'amenities' => fn (Blueprint $t) => $t->json('amenities')->nullable(),
                'hero_image' => fn (Blueprint $t) => $t->string('hero_image')->nullable(),
                'gallery' => fn (Blueprint $t) => $t->json('gallery')->nullable(),
                'website' => fn (Blueprint $t) => $t->string('website')->nullable(),
                'gps' => fn (Blueprint $t) => $t->string('gps')->nullable(),
                'rates' => fn (Blueprint $t) => $t->json('rates')->nullable(),
            ] as $column => $definition) {
                if (! Schema::hasColumn('hotels', $column)) {
                    $definition($table);
                }
            }

            if (! Schema::hasColumn('hotels', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }
};
