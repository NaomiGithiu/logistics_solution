<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->string('color');
            $table->string('plate_number')->unique();
            $table->string('vin_number')->unique();
            $table->enum('insurance_status', ['active', 'expired', 'pending'])->default('pending');
            $table->date('insurance_expiry')->nullable();
            $table->enum('vehicle_type', ['sedan', 'suv', 'hatchback', 'minivan'])->default('sedan');
            $table->tinyInteger('capacity')->default(4);
            $table->enum('status', ['available', 'in_ride', 'maintenance'])->default('available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
};
