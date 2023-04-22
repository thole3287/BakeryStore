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
        Schema::table('footer', function (Blueprint $table) {
            // $table->string('title_branch')->nullable()->after('description_2');
            $table->string('title_5')->nullable()->after('description_4');
            $table->string('description_5')->nullable()->after('title_5');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['title_5', 'description_5']);
        });
    }
};
