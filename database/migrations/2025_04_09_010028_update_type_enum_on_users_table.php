<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTypeEnumOnUsersTable extends Migration
{
    public function up()
    {
        // Modificar el campo `type` para aceptar 'Admin', 'User' y 'Supervisor'
        DB::statement("ALTER TABLE users MODIFY type ENUM('Admin', 'User', 'Supervisor') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY type ENUM('Admin', 'User') NOT NULL");
    }
}