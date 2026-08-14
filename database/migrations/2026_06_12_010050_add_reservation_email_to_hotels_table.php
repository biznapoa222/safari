<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hotels') && !Schema::hasColumn('hotels', 'reservation_email')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->string('reservation_email')->nullable()->after('website');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('hotels') && Schema::hasColumn('hotels', 'reservation_email')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->dropColumn('reservation_email');
            });
        }
    }
};
