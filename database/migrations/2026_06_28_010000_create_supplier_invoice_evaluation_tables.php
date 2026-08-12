<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reservation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('invoice_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('company_name');
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('invoice_type')->default('normal');
            $table->decimal('vat_rate', 6, 2)->default(0);
            $table->boolean('vat_reclaimable')->default(false);
            $table->text('comments')->nullable();
            $table->string('file_path')->nullable();
            $table->date('payment_deadline')->nullable();
            $table->string('status')->default('uploaded');
            $table->text('issue_notes')->nullable();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();

            $table->index(['quotation_id', 'status']);
            $table->index('payment_deadline');
        });

        Schema::create('evaluation_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quotation_day_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('quotation_item_id')->nullable()->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('reservation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('item_type');
            $table->string('title');
            $table->string('supplier')->nullable();
            $table->date('service_date')->nullable();
            $table->date('service_end_date')->nullable();
            $table->decimal('system_rate', 14, 2)->default(0);
            $table->decimal('invoice_rate', 14, 2)->nullable();
            $table->decimal('discrepancy', 14, 2)->default(0);
            $table->string('meal_plan')->nullable();
            $table->string('room_configuration')->nullable();
            $table->string('room_type')->nullable();
            $table->boolean('rate_matches')->nullable();
            $table->boolean('dates_match')->nullable();
            $table->boolean('meal_plan_matches')->nullable();
            $table->boolean('room_configuration_matches')->nullable();
            $table->boolean('room_type_matches')->nullable();
            $table->string('status')->default('missing_invoice');
            $table->text('issue_notes')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();

            $table->index(['quotation_id', 'status']);
        });

        Schema::create('proposal_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_evaluations');
        Schema::dropIfExists('evaluation_entries');
        Schema::dropIfExists('supplier_invoices');
    }
};
