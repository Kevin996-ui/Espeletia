<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewVisitorsTable extends Migration
{
    public function up()
    {
        Schema::create('new_visitors', function (Blueprint $table) {
            $table->id();  // Campo de ID auto-incremental
            $table->string('visitor_name');  // Nombre del visitante
            $table->string('visitor_company');  // Empresa del visitante
            $table->string('visitor_identity_card');  // Cédula de identidad
            $table->dateTime('visitor_enter_time');  // Hora de entrada
            $table->dateTime('visitor_out_time')->nullable();  // Hora de salida (opcional)
            $table->string('visitor_reason_to_meet');  // Motivo de la visita
            $table->string('visitor_photo')->nullable();  // Foto (opcional)
            $table->timestamps();  // Campos created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('new_visitors');
    }
}
