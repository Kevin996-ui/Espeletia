<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentIdToNewVisitorsTable extends Migration
{
    public function up()
    {
        Schema::table('new_visitors', function (Blueprint $table) {
            // Agregar la columna department_id como una clave foránea
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('new_visitors', function (Blueprint $table) {
            // Eliminar la clave foránea y la columna department_id si se revierte la migración
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
}
