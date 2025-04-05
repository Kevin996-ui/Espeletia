<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaAndEmailToKeyTypesTable extends Migration
{
    public function up()
    {
        Schema::table('key_types', function (Blueprint $table) {
            $table->string('area')->nullable();
            $table->string('email')->nullable();
        });
    }

    public function down()
    {
        Schema::table('key_types', function (Blueprint $table) {
            $table->dropColumn(['area', 'email']);
        });
    }
}