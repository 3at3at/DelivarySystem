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
    Schema::create('drivers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('phone')->nullable();
        $table->string('vehicle_type');
        $table->string('plate_number');
        $table->enum('pricing_model', ['fixed', 'per_km']);
        $table->decimal('price', 8, 2);
        $table->string('working_hours')->nullable(); // e.g., 8am-6pm
        $table->string('location')->nullable(); // e.g., Beirut
        $table->boolean('is_available')->default(true);
        $table->text('fcm_token')->nullable();          // for Firebase push notifications
        $table->timestamp('scheduled_at')->nullable();  // for calendar sync

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
