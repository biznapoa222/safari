<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mailer')->default('smtp');
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->string('encryption')->nullable();
            $table->string('username')->nullable();
            $table->text('password')->nullable();
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->string('reply_to_address')->nullable();
            $table->string('reply_to_name')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('sent_emails', function (Blueprint $table) {
            $table->id();
            $table->string('category', 64)->index(); // ready_to_book, pre_confirmation, confirmation, enquiry, proposal, etc.
            $table->string('subject');
            $table->string('to_email');
            $table->string('to_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('related_type')->nullable(); // quotation, request, lead, etc.
            $table->unsignedBigInteger('related_id')->nullable();
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body')->nullable();
            $table->string('status')->default('sent'); // sent, failed, queued
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sent_emails');
        Schema::dropIfExists('mail_settings');
    }
};
