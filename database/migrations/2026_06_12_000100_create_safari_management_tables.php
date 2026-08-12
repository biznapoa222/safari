<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->string('code', 5)->primary();
            $table->string('name');
            $table->string('native_name');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('preferred_language', 5)->default('en');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('travel_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('destination');
            $table->date('travel_date')->nullable();
            $table->unsignedSmallInteger('travelers')->default(2);
            $table->decimal('budget', 12, 2)->nullable();
            $table->string('status')->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_request_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('title');
            $table->decimal('quoted_amount', 12, 2);
            $table->decimal('estimated_cost', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('draft');
            $table->date('valid_until')->nullable();
            $table->timestamps();
        });

        Schema::create('departures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference')->unique();
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('travelers')->default(2);
            $table->string('lead_guest');
            $table->string('status')->default('confirmed');
            $table->string('guide_name')->nullable();
            $table->string('vehicle')->nullable();
            $table->timestamps();
        });

        Schema::create('safari_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->dateTime('due_at');
            $table->string('priority')->default('normal');
            $table->string('status')->default('pending');
            $table->string('assigned_to')->nullable();
            $table->timestamps();
        });

        Schema::create('content_items', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->string('name');
            $table->string('country')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->default('draft');
            $table->decimal('price_from', 12, 2)->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->unsignedSmallInteger('duration_days')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('content_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_item_id')->constrained()->cascadeOnDelete();
            $table->string('language_code', 5);
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('slug');
            $table->string('status')->default('draft');
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('upgraded_at')->nullable();
            $table->timestamps();
            $table->unique(['content_item_id', 'language_code']);
            $table->unique(['language_code', 'slug']);
        });

        Schema::create('website_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('country')->nullable();
            $table->string('destination')->nullable();
            $table->date('travel_date')->nullable();
            $table->unsignedSmallInteger('travelers')->default(2);
            $table->string('language_code', 5)->default('en');
            $table->string('source')->default('website');
            $table->string('status')->default('new');
            $table->text('message')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('event');
            $table->string('subject');
            $table->string('description');
            $table->string('user_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
        Schema::dropIfExists('website_enquiries');
        Schema::dropIfExists('content_translations');
        Schema::dropIfExists('content_items');
        Schema::dropIfExists('safari_tasks');
        Schema::dropIfExists('departures');
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('travel_requests');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('languages');
    }
};
