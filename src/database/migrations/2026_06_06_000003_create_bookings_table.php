<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billiard_table_id')->constrained('billiard_tables')->cascadeOnDelete();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('customer_name');
            $table->string('phone_number');
            $table->enum('payment_method', ['cash', 'transfer']);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->unique(['billiard_table_id', 'booking_date', 'start_time'], 'unique_table_booking_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
