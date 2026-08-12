<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('hero_image')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->json('featured_destinations')->nullable();
            $table->json('featured_safaris')->nullable();
            $table->json('featured_activities')->nullable();
            $table->boolean('show_published_accommodation')->default(true);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('open_graph_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
