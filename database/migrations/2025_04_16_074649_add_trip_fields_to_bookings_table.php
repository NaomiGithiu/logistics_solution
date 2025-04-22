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
            Schema::table('bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('trip_id')->nullable()->after('id');
                $table->boolean('is_bulk')->default(false)->after('trip_id');
                $table->text('failure_reason')->nullable()->after('status');
        
                $table->foreign('trip_id')->references('id')->on('trips');
            });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropForeign(['trip_id']);
        $table->dropColumn(['trip_id', 'is_bulk', 'status', 'failure_reason']);
    });
}

};
