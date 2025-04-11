<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporate__companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('corporate_id')->unique();
            $table->string('corporate_email');
            $table->string('contact_person');
            $table->unsignedMediumInteger('contact');
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('corporate__companies');
    }
};
