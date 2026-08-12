<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('proposal_versions')) {
            Schema::create('proposal_versions', function (Blueprint $table) {
                $table->id();
                $table->morphs('proposalable');
                $table->integer('version_number');
                $table->json('snapshot_data');
                $table->longText('rendered_html')->nullable();
                $table->string('pdf_path')->nullable();
                $table->string('checksum', 64)->nullable();
                $table->string('status')->default('draft');
                $table->timestamp('sent_at')->nullable();
                $table->timestamp('viewed_at')->nullable();
                $table->timestamp('accepted_at')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('proposal_acceptances')) {
            Schema::create('proposal_acceptances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proposal_version_id')->constrained('proposal_versions')->cascadeOnDelete();
                $table->string('customer_name');
                $table->string('customer_email');
                $table->timestamp('accepted_at');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('signature')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('proposal_change_requests')) {
            Schema::create('proposal_change_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proposal_version_id')->constrained('proposal_versions')->cascadeOnDelete();
                $table->string('customer_name');
                $table->string('customer_email');
                $table->text('message');
                $table->text('requested_changes')->nullable();
                $table->string('preferred_contact')->nullable();
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('itinerary_highlights')) {
            Schema::create('itinerary_highlights', function (Blueprint $table) {
                $table->id();
                $table->morphs('highlightable');
                $table->index(['highlightable_type', 'highlightable_id'], 'highlightable_idx');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('itinerary_day_gallery_images')) {
            Schema::create('itinerary_day_gallery_images', function (Blueprint $table) {
                $table->id();
                $table->morphs('galleryable');
                $table->index(['galleryable_type', 'galleryable_id'], 'day_gallery_idx');
                $table->string('image_path');
                $table->string('caption')->nullable();
                $table->string('credit')->nullable();
                $table->integer('sort_order')->default(0);
                $table->string('layout')->default('featured');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('proposal_template_settings')) {
            Schema::create('proposal_template_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('itinerary_template_id')->constrained()->cascadeOnDelete();
                $table->json('settings')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_template_settings');
        Schema::dropIfExists('itinerary_day_gallery_images');
        Schema::dropIfExists('itinerary_highlights');
        Schema::dropIfExists('proposal_change_requests');
        Schema::dropIfExists('proposal_acceptances');
        Schema::dropIfExists('proposal_versions');
    }
};
