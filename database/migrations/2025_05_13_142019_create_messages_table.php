<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('messages', function (Blueprint $table) {
        $table->id();
      //  $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('sender_id');
        $table->enum('sender_type', ['client', 'driver']);
        $table->text('message');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
