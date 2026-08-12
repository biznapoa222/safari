<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ========== COUNTRIES & REGIONS ==========
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['country_id', 'slug']);
        });

        // ========== SUPPLIERS ==========
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // accommodation, activity, transport, airline, guide, transfer, restaurant, charter
            $table->string('name');
            $table->string('country');
            $table->string('region')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('gps_coordinates')->nullable();
            $table->string('classification')->nullable(); // luxury, premium, mid_range, budget, land_cruiser, van, etc.
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ========== ACCOMMODATION ==========
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // hotel, lodge, camp, luxury_camp, villa, resort
            $table->string('country');
            $table->string('region')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('luxury_level')->nullable(); // luxury, premium, mid_range, budget
            $table->string('currency', 3)->default('USD');
            $table->boolean('published')->default(false);
            $table->boolean('featured')->default(false);
            $table->json('images')->nullable();
            $table->json('metadata')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('accommodation_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accommodation_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedSmallInteger('capacity')->default(2);
            $table->unsignedSmallInteger('max_adults')->default(2);
            $table->unsignedSmallInteger('max_children')->default(0);
            $table->text('child_policy')->nullable();
            $table->unsignedSmallInteger('inventory')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('accommodation_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accommodation_room_id')->constrained()->cascadeOnDelete();
            $table->string('season_name');
            $table->date('valid_from');
            $table->date('valid_to');
            $table->string('meal_plan')->default('Full Board');
            $table->decimal('rate', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ========== ACTIVITIES ==========
        Schema::create('activity_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('activity_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('country');
            $table->string('region')->nullable();
            $table->string('location');
            $table->unsignedSmallInteger('min_pax')->default(1);
            $table->unsignedSmallInteger('min_age')->default(0);
            $table->unsignedSmallInteger('duration_hours')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->boolean('published_on_website')->default(false);
            $table->boolean('show_on_mobile_app')->default(false);
            $table->string('price_status_current_year')->nullable();
            $table->string('price_status_next_year')->nullable();
            $table->string('payment_scheme_status')->nullable();
            $table->string('activity_status')->default('active');
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->text('tags')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('activity_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title');
            $table->text('description')->nullable();
            $table->unique(['activity_id', 'locale']);
        });

        Schema::create('activity_supplier', function (Blueprint $table) {
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->primary(['activity_id', 'supplier_id']);
        });

        // ========== PRICING ==========
        Schema::create('activity_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // standard, resident, non_resident, child, group
            $table->string('season'); // high, low, peak
            $table->integer('year');
            $table->decimal('price', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // high, low, peak
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });

        // ========== PAYMENT SCHEMES ==========
        Schema::create('payment_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('schemeable_type');
            $table->unsignedBigInteger('schemeable_id');
            $table->decimal('deposit_percent', 5, 2)->default(50);
            $table->text('full_payment_rules')->nullable();
            $table->text('cancellation_rules')->nullable();
            $table->timestamps();
            $table->index(['schemeable_type', 'schemeable_id']);
        });

        // ========== LEADS / CRM ==========
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('source')->default('website'); // website, whatsapp, email, referral, social_media
            $table->string('status')->default('new'); // new, contacted, proposal_sent, negotiating, confirmed, lost
            $table->foreignId('assigned_consultant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->decimal('estimated_value', 12, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->date('travel_date')->nullable();
            $table->unsignedSmallInteger('travelers')->default(2);
            $table->string('destination')->nullable();
            $table->text('interests')->nullable();
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('quotation_sent_at')->nullable();
            $table->timestamp('booking_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ========== CONVERSATIONS ==========
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->string('channel'); // email, whatsapp, phone_call, internal_note
            $table->text('content');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('direction')->default('incoming'); // incoming, outgoing
            $table->json('attachments')->nullable();
            $table->timestamps();
        });

        // ========== BOOKINGS ==========
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->string('status')->default('draft'); // draft, quotation_sent, pending_deposit, deposit_paid, partially_paid, fully_paid, confirmed, cancelled
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedSmallInteger('guests')->default(2);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->decimal('amount_paid', 14, 2)->default(0);
            $table->decimal('balance', 14, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_status')->default('unpaid'); // unpaid, partial, paid
            $table->foreignId('assigned_consultant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->boolean('cancellation_policy_accepted')->default(false);
            $table->timestamp('cancellation_accepted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ========== BOOKING ITEMS ==========
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('itemable_type'); // activity, accommodation, transport
            $table->unsignedBigInteger('itemable_id');
            $table->string('title');
            $table->date('date');
            $table->decimal('price', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ========== PAYMENTS ==========
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('method'); // credit_card, bank_transfer, paypal, wise, stripe, flutterwave, pesapal, mpesa
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->string('type')->default('payment'); // payment, deposit, balance
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ========== PAYMENT LINKS ==========
        Schema::create('payment_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->string('type'); // payment, deposit, balance
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('USD');
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });

        // ========== ITINERARY BUILDER ==========
        Schema::create('itineraries_v2', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->unsignedSmallInteger('duration_days')->default(1);
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->decimal('price_from', 12, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('published')->default(false);
            $table->boolean('featured')->default(false);
            $table->json('images')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('itinerary_days_v2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_v2_id')->constrained('itineraries_v2')->cascadeOnDelete();
            $table->unsignedSmallInteger('day_number');
            $table->string('title');
            $table->string('location')->nullable();
            $table->unsignedBigInteger('accommodation_id')->nullable();
            $table->text('activities')->nullable();
            $table->string('meal_plan')->nullable();
            $table->text('transfers')->nullable();
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ========== AUDIT LOG ==========
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // login, logout, create, edit, delete, approve, reject
            $table->string('module');
            $table->string('description')->nullable();
            $table->nullableMorphs('auditable');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

        // ========== WEBSITE CMS ==========
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type'); // page, blog, destination
            $table->text('content')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('website_publishings', function (Blueprint $table) {
            $table->id();
            $table->string('publishable_type');
            $table->unsignedBigInteger('publishable_id');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['publishable_type', 'publishable_id']);
        });

        // ========== EXCHANGE RATES ==========
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency', 3);
            $table->string('to_currency', 3);
            $table->decimal('rate', 14, 6);
            $table->date('date');
            $table->timestamps();
            $table->unique(['from_currency', 'to_currency', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
        Schema::dropIfExists('website_publishings');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('itinerary_days_v2');
        Schema::dropIfExists('itineraries_v2');
        Schema::dropIfExists('payment_links');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('booking_items');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('payment_schemes');
        Schema::dropIfExists('activity_seasons');
        Schema::dropIfExists('activity_prices');
        Schema::dropIfExists('activity_supplier');
        Schema::dropIfExists('activity_translations');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('activity_categories');
        Schema::dropIfExists('accommodation_rates');
        Schema::dropIfExists('accommodation_rooms');
        Schema::dropIfExists('accommodations');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('countries');
    }
};
