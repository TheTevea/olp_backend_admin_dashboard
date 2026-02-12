<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_seat_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_schedule_id')->constrained('bus_schedules')->onDelete('cascade');
            $table->string('seat_number');
            $table->enum('status', ['available', 'booked', 'locked'])->default('available');
            $table->foreignId('t_ticket_id')->nullable()->constrained('t_tickets')->onDelete('set null');
            $table->dateTime('locked_until')->nullable();
            $table->timestamps();
            
            // Unique constraint to prevent double booking
            $table->unique(['bus_schedule_id', 'seat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_seat_controls');
    }
};
