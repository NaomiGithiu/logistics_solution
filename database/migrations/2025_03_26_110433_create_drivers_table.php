<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Links to users table
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('password');
            $table->string('profile_picture')->nullable();
            $table->string('license_number')->unique();
            $table->date('license_expiry');
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'banned', 'pending'])->default('pending');
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('total_rides')->default(0);
            $table->decimal('earnings', 10, 2)->default(0.00);
            $table->decimal('location_lat', 10, 7)->nullable();
            $table->decimal('location_lng', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};
