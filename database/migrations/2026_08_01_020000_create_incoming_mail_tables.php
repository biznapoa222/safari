<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('incoming_mail_accounts')) {
            return;
        }

        Schema::create('incoming_mail_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('protocol')->default('imap');
            $table->string('host');
            $table->integer('port')->default(993);
            $table->string('encryption')->default('ssl');
            $table->string('username');
            $table->text('password');
            $table->string('folder')->default('INBOX');
            $table->boolean('is_active')->default(true);
            $table->boolean('mark_seen')->default(true);
            $table->boolean('delete_after_fetch')->default(false);
            $table->timestamp('last_fetched_at')->nullable();
            $table->unsignedBigInteger('last_uid')->default(0);
            $table->boolean('auto_create_request')->default(false);
            $table->foreignId('assigned_consultant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('error')->nullable();
            $table->timestamps();
        });

        Schema::create('incoming_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->nullable()->constrained('incoming_mail_accounts')->nullOnDelete();
            $table->string('message_id')->nullable()->index();
            $table->unsignedBigInteger('uid')->nullable();
            $table->string('from_email')->nullable()->index();
            $table->string('from_name')->nullable();
            $table->string('to_email')->nullable()->index();
            $table->string('subject')->nullable();
            $table->longText('body_text')->nullable();
            $table->longText('body_html')->nullable();
            $table->json('headers')->nullable();
            $table->timestamp('received_at')->nullable()->index();
            $table->string('status')->default('new');
            $table->text('notes')->nullable();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('request_id')->nullable()->constrained('requests')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incoming_emails');
        Schema::dropIfExists('incoming_mail_accounts');
    }
};
