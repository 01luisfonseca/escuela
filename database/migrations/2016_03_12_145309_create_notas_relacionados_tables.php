<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasRelacionadosTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tipo_nota')) {
        Schema::create('tipo_nota', function (Blueprint $table) {
            //Creacion de columnas
            $table->increments('id')->unique();
            $table->string('nombre');
            $table->mediumText('descripcion');
            $table->integer('indicadores_id');
            $table->timestamps();
            
        });
        }
        if (!Schema::hasTable('indicadores')) {
        Schema::create('indicadores', function (Blueprint $table) {
            //Creacion de columnas
            $table->increments('id')->unique();
            $table->string('nombre');
            $table->mediumText('descripcion');
            $table->float('porcentaje');
            $table->integer('niveles_has_periodos_id');
            $table->timestamps();
            
        });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('tipo_nota');
        Schema::dropIfExists('indicadores');
    }
}
