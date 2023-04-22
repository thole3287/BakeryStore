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
        Schema::create('footer', function (Blueprint $table) {
            $table->id();
            $table->string('title_1')->nullable();
            $table->string('description_1')->nullable();
            $table->string('title_2')->nullable();
            $table->string('description_2')->nullable();
            $table->string('title_3')->nullable();
            $table->string('description_3')->nullable();
            $table->string('title_4')->nullable();
            $table->string('description_4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer');
    }
};
