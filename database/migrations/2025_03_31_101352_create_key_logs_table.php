<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeReturnFieldsNullableInKeyLogsTable extends Migration
{
    public function up()
    {
        Schema::table('key_logs', function (Blueprint $table) {
            $table->string('name_returned')->nullable()->change();
            $table->string('identity_card_returned')->nullable()->change();
            $table->string('returned_photo')->nullable()->change();
            $table->dateTime('key_returned_at')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('key_logs', function (Blueprint $table) {
            $table->string('name_returned')->nullable(false)->change();
            $table->string('identity_card_returned')->nullable(false)->change();
            $table->string('returned_photo')->nullable(false)->change();
            $table->dateTime('key_returned_at')->nullable(false)->change();
        });
    }
}