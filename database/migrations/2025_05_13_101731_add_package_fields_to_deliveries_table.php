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
        $table->string('package_type')->nullable();
        $table->decimal('package_weight', 5, 2)->nullable();
        $table->string('package_dimensions')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            //
        });
    }
};
