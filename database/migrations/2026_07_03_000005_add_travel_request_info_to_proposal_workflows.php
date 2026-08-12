<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_workflows', function (Blueprint $table) {
            $table->text('customer_message')->nullable(); $table->string('arrival_time',10)->nullable();
            $table->string('arrival_location')->nullable(); $table->string('arrival_flight')->nullable();
            $table->string('departure_time',10)->nullable(); $table->string('departure_location')->nullable();
            $table->string('departure_flight')->nullable(); $table->text('dietary_requests')->nullable();
            $table->text('announcements')->nullable(); $table->text('customer_notes')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('proposal_workflows', fn(Blueprint $table)=>$table->dropColumn(['customer_message','arrival_time','arrival_location','arrival_flight','departure_time','departure_location','departure_flight','dietary_requests','announcements','customer_notes']));
    }
};
