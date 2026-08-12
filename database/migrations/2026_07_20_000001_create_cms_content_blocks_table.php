<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cms_content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('page', 80);
            $table->string('key', 120);
            $table->string('type', 20)->default('text');
            $table->longText('value')->nullable();
            $table->timestamps();
            $table->unique(['page', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_content_blocks');
    }
};
