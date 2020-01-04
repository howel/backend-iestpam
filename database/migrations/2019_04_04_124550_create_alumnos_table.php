<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('carreras_id');
            $table->foreign('carreras_id')->references('id')->on('carreras');
            $table->string('dni', 8)->unique();
            $table->string('nombres', 45);
            $table->string('apellidos', 60);
            $table->string('email', 100);
            $table->integer('sexo')->default(0);
            $table->integer('edad');
            $table->date('nacimiento');
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumnos');
    }
}
