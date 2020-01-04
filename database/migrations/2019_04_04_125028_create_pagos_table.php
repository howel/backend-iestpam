<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('matriculas_id');
            $table->foreign('matriculas_id')->references('id')->on('matriculas');
            $table->unsignedBigInteger('tipo_pagos_id');
            $table->foreign('tipo_pagos_id')->references('id')->on('tipo_pagos');
            $table->string('numero_vaucher', 45)->nullable();
            $table->date('fecha_vaucher')->nullable();
            $table->decimal('importe', 12, 2);
            $table->string('src_img', 255)->nullable();
            $table->text('observacion')->nullable();
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
        Schema::dropIfExists('pagos');
    }
}
