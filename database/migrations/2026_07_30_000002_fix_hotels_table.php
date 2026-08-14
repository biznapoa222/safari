<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Preserve operational hotels schema while adding CMS/template columns.
 * Earlier versions of this migration dropped hotels and broke ops seeders/quotations.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hotels')) {
            return;
        }

        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'destination_id')) {
                $table->unsignedBigInteger('destination_id')->nullable()->after('name');
            }
            if (! Schema::hasColumn('hotels', 'star_rating')) {
                $table->integer('star_rating')->default(3)->nullable();
            }
            if (! Schema::hasColumn('hotels', 'tier')) {
                $table->string('tier')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'meal_plan')) {
                $table->string('meal_plan')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'description')) {
                $table->text('description')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'amenities')) {
                $table->json('amenities')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'hero_image')) {
                $table->string('hero_image')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'gallery')) {
                $table->json('gallery')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'website')) {
                $table->string('website')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'gps')) {
                $table->string('gps')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'rates')) {
                $table->json('rates')->nullable();
            }
            if (! Schema::hasColumn('hotels', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Ensure template tables use itinerary_template_id (not legacy template_id).
        if (Schema::hasTable('template_days') && Schema::hasColumn('template_days', 'template_id') && ! Schema::hasColumn('template_days', 'itinerary_template_id')) {
            Schema::table('template_days', function (Blueprint $table) {
                $table->unsignedBigInteger('itinerary_template_id')->nullable()->after('id');
            });

            if (Schema::getConnection()->getDriverName() === 'sqlite') {
                \Illuminate\Support\Facades\DB::statement('UPDATE template_days SET itinerary_template_id = template_id');
            } else {
                \Illuminate\Support\Facades\DB::statement('UPDATE template_days SET itinerary_template_id = template_id WHERE itinerary_template_id IS NULL');
            }
        }

        if (! Schema::hasTable('proposal_template_settings')) {
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
        // Non-destructive forward migration; nothing safe to reverse automatically.
    }
};
