<?php

// database/migrations/YYYY_MM_DD_create_ratings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visitor_id');  // Relacionado con la tabla new_visitors
            $table->integer('rating');  // Calificación (de 1 a 5)
            $table->text('comment')->nullable();  // Comentario opcional
            $table->timestamps();

            // Definir la clave foránea hacia la tabla new_visitors
            $table->foreign('visitor_id')->references('id')->on('new_visitors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
