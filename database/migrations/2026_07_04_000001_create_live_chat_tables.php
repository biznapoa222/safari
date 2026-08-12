<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_conversations', function(Blueprint $table){$table->id();$table->string('token',64)->unique();$table->string('visitor_name')->nullable();$table->string('visitor_email')->nullable();$table->string('status')->default('open');$table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();$table->timestamp('last_message_at')->nullable();$table->timestamps();});
        Schema::create('chat_messages', function(Blueprint $table){$table->id();$table->foreignId('conversation_id')->constrained('chat_conversations')->cascadeOnDelete();$table->string('sender');$table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$table->text('body');$table->timestamp('read_at')->nullable();$table->timestamps();});
    }
    public function down(): void { Schema::dropIfExists('chat_messages'); Schema::dropIfExists('chat_conversations'); }
};
