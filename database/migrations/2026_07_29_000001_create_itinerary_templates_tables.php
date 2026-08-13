<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('itinerary_templates')) {
            Schema::create('itinerary_templates', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('destination')->nullable();
                $table->integer('duration_days')->default(1);
                $table->string('category')->nullable();
                $table->text('overview')->nullable();
                $table->text('highlights')->nullable();
                $table->text('includes')->nullable();
                $table->text('excludes')->nullable();
                $table->text('terms')->nullable();
                $table->text('cancellation_policy')->nullable();
                $table->text('notes')->nullable();
                $table->string('status')->default('active');
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('template_days')) {
            Schema::create('template_days', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('itinerary_template_id')->nullable();
                $table->unsignedBigInteger('template_id')->nullable();
                $table->integer('day_number');
                $table->string('title')->nullable();
                $table->string('destination')->nullable();
                $table->string('accommodation')->nullable();
                $table->string('meal_plan')->nullable();
                $table->string('morning_activity')->nullable();
                $table->string('afternoon_activity')->nullable();
                $table->string('evening_activity')->nullable();
                $table->text('description')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index('itinerary_template_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('template_days');
        Schema::dropIfExists('itinerary_templates');
    }
};
