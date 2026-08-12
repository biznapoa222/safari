<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_travelers', function (Blueprint $table) {
            $table->id(); $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->string('salutation', 20)->nullable(); $table->string('first_name'); $table->string('surname');
            $table->date('date_of_birth')->nullable(); $table->timestamps();
        });
        Schema::create('proposal_adjustments', function (Blueprint $table) {
            $table->id(); $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->string('type'); $table->string('description'); $table->string('calculation_type')->default('fixed_price');
            $table->decimal('unit_amount', 14, 2)->default(0); $table->decimal('quantity', 10, 2)->default(1);
            $table->string('currency', 3)->default('USD'); $table->text('notes')->nullable(); $table->timestamps();
        });
        Schema::create('proposal_documents', function (Blueprint $table) {
            $table->id(); $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category'); $table->string('file_name'); $table->string('path');
            $table->string('mime_type')->nullable(); $table->unsignedBigInteger('size')->default(0); $table->timestamps();
        });
        Schema::create('proposal_snapshots', function (Blueprint $table) {
            $table->id(); $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status'); $table->decimal('price', 14, 2)->default(0); $table->decimal('exchange_rate', 12, 6)->default(1);
            $table->string('label')->nullable(); $table->json('snapshot_data'); $table->timestamps();
        });
        Schema::create('reservation_emails', function (Blueprint $table) {
            $table->id(); $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('recipient'); $table->string('subject'); $table->text('message');
            $table->string('status')->default('sent'); $table->text('error')->nullable(); $table->timestamp('sent_at')->nullable(); $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_emails'); Schema::dropIfExists('proposal_snapshots');
        Schema::dropIfExists('proposal_documents'); Schema::dropIfExists('proposal_adjustments'); Schema::dropIfExists('proposal_travelers');
    }
};
