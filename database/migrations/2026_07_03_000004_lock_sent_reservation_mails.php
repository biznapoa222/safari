<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->timestamp('reservation_mail_sent_at')->nullable()->after('notes');
            $table->string('reservation_mail_recipient')->nullable()->after('reservation_mail_sent_at');
        });
    }
    public function down(): void
    {
        Schema::table('reservations', fn (Blueprint $table) => $table->dropColumn(['reservation_mail_sent_at','reservation_mail_recipient']));
    }
};
