<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('room_types')) {
            Schema::table('room_types', function (Blueprint $table) {
                if (! Schema::hasColumn('room_types', 'baby_max_age')) {
                    $table->unsignedSmallInteger('baby_max_age')->default(2)->after('max_children');
                }
                if (! Schema::hasColumn('room_types', 'child_min_age')) {
                    $table->unsignedSmallInteger('child_min_age')->default(3)->after('baby_max_age');
                }
                if (! Schema::hasColumn('room_types', 'child_max_age')) {
                    $table->unsignedSmallInteger('child_max_age')->default(11)->after('child_min_age');
                }
                if (! Schema::hasColumn('room_types', 'adult_min_age')) {
                    $table->unsignedSmallInteger('adult_min_age')->default(12)->after('child_max_age');
                }
            });
        }

        if (Schema::hasTable('accommodation_rooms')) {
            Schema::table('accommodation_rooms', function (Blueprint $table) {
                if (! Schema::hasColumn('accommodation_rooms', 'baby_max_age')) {
                    $table->unsignedSmallInteger('baby_max_age')->default(2)->after('max_children');
                }
                if (! Schema::hasColumn('accommodation_rooms', 'child_min_age')) {
                    $table->unsignedSmallInteger('child_min_age')->default(3)->after('baby_max_age');
                }
                if (! Schema::hasColumn('accommodation_rooms', 'child_max_age')) {
                    $table->unsignedSmallInteger('child_max_age')->default(11)->after('child_min_age');
                }
                if (! Schema::hasColumn('accommodation_rooms', 'adult_min_age')) {
                    $table->unsignedSmallInteger('adult_min_age')->default(12)->after('child_max_age');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('room_types')) {
            Schema::table('room_types', function (Blueprint $table) {
                foreach (['baby_max_age', 'child_min_age', 'child_max_age', 'adult_min_age'] as $column) {
                    if (Schema::hasColumn('room_types', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('accommodation_rooms')) {
            Schema::table('accommodation_rooms', function (Blueprint $table) {
                foreach (['baby_max_age', 'child_min_age', 'child_max_age', 'adult_min_age'] as $column) {
                    if (Schema::hasColumn('accommodation_rooms', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
