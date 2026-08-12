<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('accommodations', 'slug')) {
            Schema::table('accommodations', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique()->after('name');
            });

            DB::table('accommodations')->orderBy('id')->each(function ($row) {
                $base = Str::slug($row->name);
                $slug = $base;
                $i = 1;
                while (DB::table('accommodations')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                DB::table('accommodations')->where('id', $row->id)->update(['slug' => $slug]);
            });
        }
    }

    public function down(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
