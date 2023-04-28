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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->json('sales_by_day_of_week');
            $table->json('sales_by_day_of_month');
            $table->json('sales_by_month_of_year');
            $table->decimal('sales_last_week', 10, 2);
            $table->decimal('sales_last_month', 10, 2);
            $table->json('sales_by_month_of_previous_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
