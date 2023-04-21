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
        Schema::table('employees', function (Blueprint $table) {
            $table->mediumText('image')->nullable()->after('name');
            $table->integer('age')->nullable()->after('image');
            $table->string('gender')->nullable()->after('age');
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['image', 'age', 'gender']);
            // $table->dropColumn('image');


        });
    }
};
