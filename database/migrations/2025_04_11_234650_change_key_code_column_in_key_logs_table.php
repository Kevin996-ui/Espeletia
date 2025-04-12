<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

class ChangeKeyCodeColumnInKeyLogsTable extends Migration

{

    /**

     * Run the migrations.

     */

    public function up(): void

    {

        Schema::table('key_logs', function (Blueprint $table) {

            $table->text('key_code')->change(); // Cambiar de string(255) a text

        });

    }

    /**

     * Reverse the migrations.

     */

    public function down(): void

    {

        Schema::table('key_logs', function (Blueprint $table) {

            $table->string('key_code', 255)->change(); // Revertir si es necesario

        });

    }

}

