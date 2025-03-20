<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisitorCardToNewVisitors extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_visitors', function (Blueprint $table) {
            $table->string('visitor_card')->nullable();  // Aquí se agrega la columna visitor_card
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
            $table->dropColumn('visitor_card');  // Elimina la columna si es necesario revertir
        });
    }
}
