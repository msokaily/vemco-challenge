<?php

use App\Enums\SensorStatus;
use App\Models\Sensor;
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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', [
                SensorStatus::ACTIVE->value,
                SensorStatus::INACTIVE->value,
            ])->default(SensorStatus::ACTIVE->value);
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
