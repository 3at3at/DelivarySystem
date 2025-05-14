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
    Schema::table('deliveries', function (Blueprint $table) {
        $table->decimal('distance_km', 8, 2)->nullable()->after('dropoff_location');
    });
}

public function down(): void
{
    Schema::table('deliveries', function (Blueprint $table) {
        $table->dropColumn('distance_km');
    });
}

};
