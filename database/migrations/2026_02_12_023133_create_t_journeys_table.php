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
        Schema::create('t_journeys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('from_destination_id')->constrained('t_destinations')->onDelete('restrict');
            $table->foreignId('to_destination_id')->constrained('t_destinations')->onDelete('restrict');
            $table->string('code')->unique();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('duration_hours', 5, 2)->nullable();
            $table->decimal('base_price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_journeys');
    }
};
