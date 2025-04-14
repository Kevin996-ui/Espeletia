<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyLogsTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_logs', function (Blueprint $table) {
            $table->id();

            $table->string('name_taken');
            $table->string('identity_card_taken');
            $table->string('taken_photo');
            $table->string('area');
            $table->text('key_code');
            $table->dateTime('key_taken_at');

            $table->string('name_returned')->nullable();
            $table->string('identity_card_returned')->nullable();
            $table->string('returned_photo')->nullable();
            $table->dateTime('key_returned_at')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('key_logs');
    }
}
