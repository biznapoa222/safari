<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void { Schema::table('activity_translations',function(Blueprint $table){$table->string('location')->nullable();$table->string('region')->nullable();}); }
    public function down(): void { Schema::table('activity_translations',fn(Blueprint $table)=>$table->dropColumn(['location','region'])); }
};
