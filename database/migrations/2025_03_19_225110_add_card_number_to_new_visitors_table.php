<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCardNumberToNewVisitors extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_visitors', function (Blueprint $table) {
            $table->dropColumn('card_number');
        });
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_visitors', function (Blueprint $table) {
            $table->string('card_number')->nullable();
        });
    }
}
