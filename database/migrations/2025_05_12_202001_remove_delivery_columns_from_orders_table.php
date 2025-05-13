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
    Schema::table('orders', function (Blueprint $table) {
        // Drop foreign key before dropping column
        $table->dropForeign(['driver_id']);

        // Now drop the columns safely
        $table->dropColumn([
            'pickup_location',
            'dropoff_location',
            'driver_id',
        ]);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
