<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('ride_type', ['express', 'standard'])->default('standard');
        });
    }

    public function down()
    {
        // Drop CHECK constraints and DEFAULT constraints on the 'ride_type' column
        DB::statement("
            DECLARE @sql NVARCHAR(MAX) = '';
    
            -- Drop DEFAULT constraints
            SELECT @sql += 'ALTER TABLE [dbo].[bookings] DROP CONSTRAINT [' + dc.name + '];'
            FROM sys.default_constraints dc
            JOIN sys.columns c ON c.default_object_id = dc.object_id
            WHERE c.object_id = OBJECT_ID('bookings') AND c.name = 'ride_type';
    
            -- Drop CHECK constraints
            SELECT @sql += 'ALTER TABLE [dbo].[bookings] DROP CONSTRAINT [' + cc.name + '];'
            FROM sys.check_constraints cc
            JOIN sys.columns col ON cc.parent_object_id = col.object_id
            WHERE cc.parent_object_id = OBJECT_ID('bookings') AND col.name = 'ride_type';
    
            EXEC(@sql);
        ");
    
        // Drop the column
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('ride_type');
        });
    }
    
};

