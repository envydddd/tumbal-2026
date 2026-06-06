<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('amount')->default(0)->after('payment_method');

            $table->string('payment_status')->default('unpaid')->after('status');
            $table->string('payment_reference')->nullable()->after('payment_status');
            $table->string('payment_gateway')->nullable()->after('payment_reference');

            $table->text('qris_url')->nullable()->after('payment_gateway');
            $table->text('payment_url')->nullable()->after('qris_url');

            $table->timestamp('paid_at')->nullable()->after('payment_url');
            $table->timestamp('expired_at')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'amount',
                'payment_status',
                'payment_reference',
                'payment_gateway',
                'qris_url',
                'payment_url',
                'paid_at',
                'expired_at',
            ]);
        });
    }
};