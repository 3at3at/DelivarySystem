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
    Schema::table('deliveries', function (Blueprint $table) {
        $table->decimal('price', 12, 2)->change();
        $table->decimal('converted_price', 12, 2)->nullable()->change();
    });
}

public function down()
{
    Schema::table('deliveries', function (Blueprint $table) {
        $table->decimal('price', 8, 2)->change();
        $table->decimal('converted_price', 8, 2)->nullable()->change();
    });
}

};
