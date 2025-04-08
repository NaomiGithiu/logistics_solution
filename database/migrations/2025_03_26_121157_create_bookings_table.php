<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->decimal('estimated_fare', 10, 2)->nullable();
            $table->enum('vehicle_type', ['bike', 'van', 'truck', 'taxi']);
            $table->timestamp('scheduled_time')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'canceled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
