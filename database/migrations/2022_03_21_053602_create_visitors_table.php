<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorsTable extends Migration
{
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_name');
            $table->string('visitor_email');
            $table->string('visitor_mobile_no');
            $table->string('visitor_address');
            $table->string('visitor_meet_person_name');
            $table->string('visitor_department');
            $table->string('visitor_reason_to_meet');
            $table->dateTime('visitor_enter_time');
            $table->string('visitor_outing_remark')->nullable();
            $table->dateTime('visitor_out_time')->nullable();
            $table->enum('visitor_status', ['In', 'Out']);
            $table->unsignedBigInteger('visitor_enter_by');
            $table->string('visitor_photo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitors');
    }
}
