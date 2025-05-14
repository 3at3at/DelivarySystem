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
        Schema::table('messages', function (Blueprint $table) {
            $table->string('sender_type', 100)->change(); // increase to 100 characters
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('sender_type', 20)->change(); // revert if needed
        });
    }
};
