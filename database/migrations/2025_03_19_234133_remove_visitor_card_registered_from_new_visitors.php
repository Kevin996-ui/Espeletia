<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveVisitorCardRegisteredFromNewVisitors extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_visitors', function (Blueprint $table) {
            $table->dropColumn('visitor_card_registered');
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
            $table->boolean('visitor_card_registered')->default(false); // o el tipo de dato original y valor por defecto
        });
    }
}
