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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->string('package_details')->nullable();
            $table->enum('status', ['Pending', 'Accepted', 'In Progress', 'Delivered', 'Cancelled'])->default('Pending');
            $table->timestamp('scheduled_at')->nullable(); // for calendar sync
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
