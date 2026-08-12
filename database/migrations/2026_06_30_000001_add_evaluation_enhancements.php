<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplier_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('supplier_invoices', 'invoice_category')) {
                $table->string('invoice_category')->nullable()->after('invoice_type');
            }
            if (!Schema::hasColumn('supplier_invoices', 'invoice_item_type')) {
                $table->string('invoice_item_type')->nullable()->after('invoice_category');
            }
            if (!Schema::hasColumn('supplier_invoices', 'exchange_rate')) {
                $table->decimal('exchange_rate', 14, 6)->default(1)->after('currency');
            }
            if (!Schema::hasColumn('supplier_invoices', 'vat_amount')) {
                $table->decimal('vat_amount', 14, 2)->default(0)->after('vat_rate');
            }
            if (!Schema::hasColumn('supplier_invoices', 'is_split_invoice')) {
                $table->boolean('is_split_invoice')->default(false)->after('vat_reclaimable');
            }
            if (!Schema::hasColumn('supplier_invoices', 'parent_invoice_id')) {
                $table->foreignId('parent_invoice_id')->nullable()->constrained('supplier_invoices')->nullOnDelete()->after('is_split_invoice');
            }
            if (!Schema::hasColumn('supplier_invoices', 'remaining_balance')) {
                $table->decimal('remaining_balance', 14, 2)->nullable()->after('amount');
            }
        });

        Schema::table('evaluation_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('evaluation_entries', 'arrival_date')) {
                $table->date('arrival_date')->nullable()->after('service_end_date');
            }
            if (!Schema::hasColumn('evaluation_entries', 'departure_date')) {
                $table->date('departure_date')->nullable()->after('arrival_date');
            }
            if (!Schema::hasColumn('evaluation_entries', 'quantity')) {
                $table->decimal('quantity', 10, 2)->default(1)->after('departure_date');
            }
            if (!Schema::hasColumn('evaluation_entries', 'number_of_rooms')) {
                $table->unsignedSmallInteger('number_of_rooms')->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('evaluation_entries', 'number_of_nights')) {
                $table->unsignedSmallInteger('number_of_nights')->nullable()->after('number_of_rooms');
            }
            if (!Schema::hasColumn('evaluation_entries', 'adults')) {
                $table->unsignedSmallInteger('adults')->nullable()->after('number_of_nights');
            }
            if (!Schema::hasColumn('evaluation_entries', 'children')) {
                $table->unsignedSmallInteger('children')->nullable()->after('adults');
            }
            if (!Schema::hasColumn('evaluation_entries', 'supplier_name')) {
                $table->string('supplier_name')->nullable()->after('supplier');
            }
            if (!Schema::hasColumn('evaluation_entries', 'variance_percent')) {
                $table->decimal('variance_percent', 8, 2)->default(0)->after('discrepancy');
            }
            if (!Schema::hasColumn('evaluation_entries', 'is_overcharge')) {
                $table->boolean('is_overcharge')->nullable()->after('variance_percent');
            }
            if (!Schema::hasColumn('evaluation_entries', 'is_undercharge')) {
                $table->boolean('is_undercharge')->nullable()->after('is_overcharge');
            }
            if (!Schema::hasColumn('evaluation_entries', 'is_mismatch')) {
                $table->boolean('is_mismatch')->nullable()->after('is_undercharge');
            }
            if (!Schema::hasColumn('evaluation_entries', 'is_duplicate_check')) {
                $table->boolean('is_duplicate_check')->default(false)->after('is_mismatch');
            }
            if (!Schema::hasColumn('evaluation_entries', 'allocated_amount')) {
                $table->decimal('allocated_amount', 14, 2)->nullable()->after('invoice_rate');
            }
        });

        Schema::table('proposal_evaluations', function (Blueprint $table) {
            if (!Schema::hasColumn('proposal_evaluations', 'evaluated_by')) {
                $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete()->after('approved_by');
            }
            if (!Schema::hasColumn('proposal_evaluations', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('evaluated_by');
            }
            if (!Schema::hasColumn('proposal_evaluations', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('started_at');
            }
            if (!Schema::hasColumn('proposal_evaluations', 'total_entries')) {
                $table->unsignedInteger('total_entries')->default(0)->after('completed_at');
            }
            if (!Schema::hasColumn('proposal_evaluations', 'matched_entries')) {
                $table->unsignedInteger('matched_entries')->default(0)->after('total_entries');
            }
            if (!Schema::hasColumn('proposal_evaluations', 'missing_invoices')) {
                $table->unsignedInteger('missing_invoices')->default(0)->after('matched_entries');
            }
            if (!Schema::hasColumn('proposal_evaluations', 'total_variance')) {
                $table->decimal('total_variance', 14, 2)->default(0)->after('missing_invoices');
            }
            if (!Schema::hasColumn('proposal_evaluations', 'reservation_officer_id')) {
                $table->foreignId('reservation_officer_id')->nullable()->constrained('users')->nullOnDelete()->after('quotation_id');
            }
        });

        Schema::create('evaluation_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_invoice_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('evaluation_entry_id')->nullable()->constrained('evaluation_entries')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('module')->default('evaluation');
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['quotation_id', 'action']);
            $table->index('created_at');
        });

        Schema::create('evaluation_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('severity')->default('info');
            $table->text('message');
            $table->json('sent_to')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['quotation_id', 'type']);
            $table->index('severity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_notifications');
        Schema::dropIfExists('evaluation_audit_logs');

        Schema::table('proposal_evaluations', function (Blueprint $table) {
            $table->dropColumn([
                'evaluated_by', 'started_at', 'completed_at',
                'total_entries', 'matched_entries', 'missing_invoices',
                'total_variance', 'reservation_officer_id',
            ]);
        });

        Schema::table('evaluation_entries', function (Blueprint $table) {
            $table->dropColumn([
                'arrival_date', 'departure_date', 'quantity', 'number_of_rooms',
                'number_of_nights', 'adults', 'children', 'supplier_name',
                'variance_percent', 'is_overcharge', 'is_undercharge', 'is_mismatch',
                'is_duplicate_check', 'allocated_amount',
            ]);
        });

        Schema::table('supplier_invoices', function (Blueprint $table) {
            $table->dropForeign(['parent_invoice_id']);
            $table->dropColumn([
                'invoice_category', 'invoice_item_type', 'exchange_rate', 'vat_amount',
                'is_split_invoice', 'parent_invoice_id', 'remaining_balance',
            ]);
        });
    }
};
