<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (! Schema::hasColumn('quotations', 'request_id')) {
                $table->foreignId('request_id')->nullable()->constrained('requests')->nullOnDelete();
            }
        });

        Schema::table('quotations', function (Blueprint $table) {
            if (! Schema::hasColumn('quotations', 'trip_theme')) {
                $table->string('trip_theme')->nullable();
            }
            if (! Schema::hasColumn('quotations', 'is_lms')) {
                $table->boolean('is_lms')->default(false);
            }
            if (! Schema::hasColumn('quotations', 'is_mobile_sale')) {
                $table->boolean('is_mobile_sale')->default(false);
            }
            if (! Schema::hasColumn('quotations', 'pre_confirmed_at')) {
                $table->dateTime('pre_confirmed_at')->nullable();
            }
            if (! Schema::hasColumn('quotations', 'pre_confirmed_by')) {
                $table->foreignId('pre_confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('quotations', 'confirmation_date')) {
                $table->dateTime('confirmation_date')->nullable();
            }
            if (! Schema::hasColumn('quotations', 'cancellation_date')) {
                $table->dateTime('cancellation_date')->nullable();
            }
            if (! Schema::hasColumn('quotations', 'valid_until')) {
                $table->date('valid_until')->nullable();
            }
        });

        // Portable backfill (MySQL JOIN update is not SQLite-compatible).
        $requestLinks = DB::table('requests')
            ->whereNotNull('converted_to_quote_id')
            ->get(['id', 'converted_to_quote_id']);

        foreach ($requestLinks as $request) {
            DB::table('quotations')
                ->where('id', $request->converted_to_quote_id)
                ->whereNull('request_id')
                ->update(['request_id' => $request->id]);
        }
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            foreach (['request_id', 'trip_theme', 'is_lms', 'is_mobile_sale', 'pre_confirmed_at', 'pre_confirmed_by', 'confirmation_date', 'cancellation_date', 'valid_until'] as $column) {
                if (Schema::hasColumn('quotations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
