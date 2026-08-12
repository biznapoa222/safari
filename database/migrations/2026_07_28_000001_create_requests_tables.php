<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('request_number')->unique();
            $table->date('request_date')->default(now()->toDateString());
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('nationality')->nullable();
            $table->string('country')->nullable();
            $table->integer('adults')->default(2);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            $table->date('arrival_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->integer('nights')->default(0);
            $table->string('destination')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->string('accommodation_tier')->nullable();
            $table->string('travel_type')->nullable();
            $table->string('source')->nullable()->default('manual');
            $table->string('language')->nullable()->default('en');
            $table->string('priority')->nullable()->default('medium');
            $table->string('status')->default('new');
            $table->integer('rating')->nullable();
            $table->boolean('is_diamond')->default(false);
            $table->string('flag_color')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_consultant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('company')->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('special_requests')->nullable();
            $table->text('seller_notes')->nullable();
            $table->decimal('quote_value', 12, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->string('transport')->nullable();
            $table->boolean('flight_required')->default(false);
            $table->boolean('pickup_required')->default(false);
            $table->boolean('guide_required')->default(false);
            $table->boolean('visa_required')->default(false);
            $table->boolean('insurance_required')->default(false);
            $table->timestamp('converted_to_quote_at')->nullable();
            $table->foreignId('converted_to_quote_id')->nullable()->constrained('quotations')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancelled_reason')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('request_number');
            $table->index('status');
            $table->index('assigned_to');
            $table->index('arrival_date');
            $table->index('client_id');
        });

        Schema::create('request_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->text('note');
            $table->string('type')->default('general');
            $table->timestamp('created_at')->nullable();

            $table->index('request_id');
        });

        Schema::create('request_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->date('deadline')->nullable();
            $table->string('priority')->default('medium');
            $table->string('status')->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('request_id');
        });

        Schema::create('request_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('filename');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('category')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('request_id');
        });

        Schema::create('request_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('field');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('request_id');
        });

        Schema::create('request_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('request_id');
        });

        Schema::create('request_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('followup_date');
            $table->string('status')->default('pending');
            $table->string('reminder_type')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('request_id');
        });

        Schema::create('request_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('color');
            $table->text('note')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('request_id');
        });

        Schema::create('request_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->string('tag');
            $table->timestamp('created_at')->nullable();

            $table->index('request_id');
            $table->index('tag');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_tags');
        Schema::dropIfExists('request_flags');
        Schema::dropIfExists('request_followups');
        Schema::dropIfExists('request_status_logs');
        Schema::dropIfExists('request_history');
        Schema::dropIfExists('request_files');
        Schema::dropIfExists('request_tasks');
        Schema::dropIfExists('request_notes');
        Schema::dropIfExists('requests');
    }
};
