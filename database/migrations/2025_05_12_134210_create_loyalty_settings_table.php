<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loyalty_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('points_per_km', 8, 2)->default(1.0);
            $table->integer('bonus_threshold')->default(100); // points needed
            $table->decimal('bonus_reward', 5, 2)->default(5.0); // reward in %
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_settings');
    }
};
