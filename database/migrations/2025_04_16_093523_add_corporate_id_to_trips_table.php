<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCorporateIdToTripsTable extends Migration
{
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('corporate_id')->nullable()->after('id');

            // Add foreign key constraint
            $table->foreign('corporate_id')
                ->references('corporate_id')
                ->on('corporate_companies');
        });
    }

    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['corporate_id']);
            $table->dropColumn('corporate_id');
        });
    }
}

