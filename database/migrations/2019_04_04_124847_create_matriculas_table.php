<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('alumnos_id');
            $table->foreign('alumnos_id')->references('id')->on('alumnos');
            $table->unsignedBigInteger('periodos_id');
            $table->foreign('periodos_id')->references('id')->on('periodos');
            $table->unsignedBigInteger('carreras_id');
            $table->foreign('carreras_id')->references('id')->on('carreras');
            $table->unsignedBigInteger('ciclos_id');
            $table->foreign('ciclos_id')->references('id')->on('ciclos');
            $table->unsignedBigInteger('tipo_matriculas_id');
            $table->foreign('tipo_matriculas_id')->references('id')->on('tipo_matriculas');
            $table->decimal('tasa_descuento', 12, 2);
            $table->decimal('total', 12, 2);
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
        Schema::dropIfExists('matriculas');
    }
}
