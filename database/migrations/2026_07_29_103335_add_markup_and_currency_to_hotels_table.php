<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hotels')) {
            return;
        }

        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'default_markup_percent')) {
                $table->decimal('default_markup_percent', 6, 2)->default(20);
            }
            if (! Schema::hasColumn('hotels', 'currency')) {
                $table->string('currency', 3)->default('USD');
            }
        });
    }

    public function down(): void
    {
        // Keep columns; they are part of the operational hotels schema.
    }
};
