<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewVisitorsTable extends Migration
{
    public function up()
    {
        Schema::create('new_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_name');
            $table->string('visitor_company');
            $table->string('visitor_identity_card');
            $table->dateTime('visitor_enter_time');
            $table->dateTime('visitor_out_time')->nullable();
            $table->string('visitor_reason_to_meet');
            $table->string('visitor_photo')->nullable();
            $table->string('visitor_card')->nullable();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->onDelete('set null');

            $table->foreignId('card_id')
                ->nullable()
                ->constrained('cards')
                ->onDelete('set null');

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('new_visitors');
    }
}
