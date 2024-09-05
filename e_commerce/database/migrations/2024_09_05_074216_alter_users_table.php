<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function(Blueprint $table){
            $table->enum('gender', ['Male', 'Female','Other'])->after('phone')->default('Male');
            $table->enum('status',['1','0'])->comment('1 for active, 0 for in-active')->after('password')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('gender');
            $table->dropColumn('status');
        });
    }
};
