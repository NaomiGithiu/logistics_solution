<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('corporate_id')->nullable()->after('id');
            $table->boolean('is_corporate_admin')->default(false)->after('corporate_id');

            $table->foreign('corporate_id')
                  ->references('corporate_id')
                  ->on('corporate_companies') 
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['corporate_id']);
            $table->dropColumn(['corporate_id', 'is_corporate_admin']);
        });
    }
};
