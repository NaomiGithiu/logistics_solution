<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('users');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->enum('status', ['pending', 'in_progress', 'delivered', 'canceled'])->default('pending');
            $table->text('cancel_reason')->nullable();
            $table->timestamp('scheduled_time')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('booking_id')->nullable(false)->index();
            $table->foreign('booking_id')->references('id')->on('bookings');
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
};
