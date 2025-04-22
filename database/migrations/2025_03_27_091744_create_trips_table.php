<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('trip_id')->unique(); // e.g. TRIP-001
            $table->unsignedBigInteger('driver_id');
            $table->string('vehicle_number');
            $table->dateTime('scheduled_time')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, in_progress, delivered, failed
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
                    
        });
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
};
