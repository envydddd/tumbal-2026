<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('customer_whatsapp_sent_at')->nullable()->after('paid_at');
            $table->timestamp('admin_whatsapp_sent_at')->nullable()->after('customer_whatsapp_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'customer_whatsapp_sent_at',
                'admin_whatsapp_sent_at',
            ]);
        });
    }
};