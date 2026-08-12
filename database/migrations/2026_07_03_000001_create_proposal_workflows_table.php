<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('country')->default('Tanzania');
            $table->string('proposal_type')->default('Itinerary');
            $table->timestamp('quotation_checked_at')->nullable();
            $table->timestamp('leader_checked_at')->nullable();
            $table->timestamp('confirmation_sent_at')->nullable();
            $table->timestamp('itinerary_completed_at')->nullable();
            $table->timestamp('jeeps_planned_at')->nullable();
            $table->timestamp('daily_movements_checked_at')->nullable();
            $table->timestamp('pre_departure_checked_at')->nullable();
            $table->text('planning_note')->nullable();
            $table->string('whatsapp_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_workflows');
    }
};
