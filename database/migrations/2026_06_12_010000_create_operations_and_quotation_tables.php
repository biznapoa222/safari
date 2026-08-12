<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country');
            $table->string('location');
            $table->string('supplier_type')->default('recommended');
            $table->string('luxury_level')->default('standard');
            $table->string('reservation_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('default_markup_percent', 6, 2)->default(20);
            $table->text('notes')->nullable();
            $table->boolean('payment_scheme_filled')->default(false);
            $table->boolean('published')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedSmallInteger('max_adults')->default(2);
            $table->unsignedSmallInteger('max_children')->default(0);
            $table->unsignedSmallInteger('inventory')->default(1);
            $table->boolean('is_family_room')->default(false);
            $table->boolean('is_interconnecting')->default(false);
            $table->boolean('active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['hotel_id', 'name']);
        });

        Schema::create('hotel_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->string('season_name');
            $table->date('valid_from');
            $table->date('valid_to');
            $table->string('meal_plan')->default('Full Board');
            $table->string('occupancy_basis')->default('per_room');
            $table->decimal('buy_rate', 12, 2);
            $table->decimal('markup_percent', 6, 2)->nullable();
            $table->decimal('sell_rate', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
        });

        Schema::create('tour_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('country');
            $table->string('location');
            $table->string('supplier')->nullable();
            $table->string('calculation_type')->default('per_person');
            $table->decimal('buy_rate', 12, 2);
            $table->decimal('markup_percent', 6, 2)->default(20);
            $table->decimal('sell_rate', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->unsignedSmallInteger('daily_capacity')->nullable();
            $table->unsignedSmallInteger('duration_hours')->default(2);
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('number_plate')->unique();
            $table->string('name');
            $table->string('type')->default('Safari Land Cruiser');
            $table->unsignedSmallInteger('capacity')->default(7);
            $table->string('current_location')->default('Nairobi');
            $table->decimal('daily_buy_rate', 12, 2)->default(0);
            $table->decimal('markup_percent', 6, 2)->default(20);
            $table->string('currency', 3)->default('USD');
            $table->string('driver_name')->nullable();
            $table->string('status')->default('available');
            $table->timestamps();
        });

        Schema::create('route_distances', function (Blueprint $table) {
            $table->id();
            $table->string('from_location');
            $table->string('to_location');
            $table->unsignedInteger('distance_km');
            $table->decimal('minimum_hours', 5, 2);
            $table->boolean('same_day_allowed')->default(true);
            $table->text('warning')->nullable();
            $table->timestamps();
            $table->unique(['from_location', 'to_location']);
        });

        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('request_reference')->unique();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('passenger_name');
            $table->string('passenger_type')->default('adult');
            $table->string('passport_number')->nullable();
            $table->string('airline');
            $table->string('flight_number');
            $table->string('flight_type')->default('domestic');
            $table->string('cabin_class')->default('economy');
            $table->string('origin_code', 3);
            $table->string('destination_code', 3);
            $table->dateTime('departure_at');
            $table->dateTime('arrival_at');
            $table->string('pnr')->nullable();
            $table->string('ticket_number')->nullable();
            $table->string('baggage_allowance')->nullable();
            $table->string('supplier')->nullable();
            $table->decimal('base_fare', 14, 2);
            $table->decimal('taxes', 14, 2)->default(0);
            $table->decimal('markup_percent', 6, 2)->default(10);
            $table->decimal('selling_total', 14, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('payment_deadline')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->string('booking_status')->default('requested');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('title');
            $table->date('start_date');
            $table->unsignedSmallInteger('duration_days');
            $table->unsignedSmallInteger('guest_count')->default(2);
            $table->string('start_location')->default('Nairobi');
            $table->string('currency', 3)->default('USD');
            $table->decimal('office_markup_percent', 6, 2)->default(20);
            $table->decimal('misc_markup_percent', 6, 2)->default(5);
            $table->decimal('exchange_rate', 12, 6)->default(1);
            $table->decimal('buy_total', 14, 2)->default(0);
            $table->decimal('sell_total', 14, 2)->default(0);
            $table->decimal('margin_total', 14, 2)->default(0);
            $table->string('status')->default('draft');
            $table->boolean('frozen')->default(false);
            $table->timestamps();
        });

        Schema::create('quotation_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('day_number');
            $table->date('travel_date');
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['quotation_id', 'day_number']);
        });

        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_day_id')->constrained()->cascadeOnDelete();
            $table->string('item_type');
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('title');
            $table->string('source')->nullable();
            $table->string('calculation_type')->default('per_person');
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('buy_unit_price', 12, 2);
            $table->decimal('markup_percent', 6, 2)->default(20);
            $table->decimal('sell_unit_price', 12, 2);
            $table->decimal('buy_total', 14, 2);
            $table->decimal('sell_total', 14, 2);
            $table->string('currency', 3)->default('USD');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quotation_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reservation_type');
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->string('supplier')->nullable();
            $table->string('confirmation_number')->nullable();
            $table->string('assigned_person')->nullable();
            $table->string('number_plate')->nullable();
            $table->decimal('amount_due', 14, 2)->default(0);
            $table->decimal('actual_cost', 14, 2)->default(0);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->date('payment_deadline')->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('quotation_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->string('reference');
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('paid_at');
            $table->string('method')->default('Bank transfer');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('trip_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->string('category');
            $table->string('supplier')->nullable();
            $table->string('description');
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('USD');
            $table->boolean('was_quoted')->default(false);
            $table->boolean('charged_to_client')->default(false);
            $table->date('expense_date');
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });

        Schema::table('website_enquiries', function (Blueprint $table) {
            $table->string('assigned_to')->nullable()->after('status');
            $table->string('lifecycle_status')->default('new')->after('assigned_to');
            $table->dateTime('next_follow_up_at')->nullable()->after('lifecycle_status');
            $table->decimal('estimated_value', 14, 2)->nullable()->after('next_follow_up_at');
            $table->foreignId('converted_quotation_id')->nullable()->after('estimated_value')->constrained('quotations')->nullOnDelete();
        });

        Schema::create('module_records', function (Blueprint $table) {
            $table->id();
            $table->string('module_slug')->index();
            $table->string('title');
            $table->string('reference')->nullable();
            $table->string('status')->default('active');
            $table->date('effective_date')->nullable();
            $table->decimal('amount', 14, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_records');
        Schema::table('website_enquiries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('converted_quotation_id');
            $table->dropColumn(['assigned_to', 'lifecycle_status', 'next_follow_up_at', 'estimated_value']);
        });
        Schema::dropIfExists('trip_expenses');
        Schema::dropIfExists('quotation_payments');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotation_days');
        Schema::dropIfExists('quotations');
        Schema::dropIfExists('flight_bookings');
        Schema::dropIfExists('route_distances');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('tour_activities');
        Schema::dropIfExists('hotel_rates');
        Schema::dropIfExists('room_types');
        Schema::dropIfExists('hotels');
    }
};
