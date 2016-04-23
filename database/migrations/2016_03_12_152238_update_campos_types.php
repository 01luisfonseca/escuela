<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCamposTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('notas', function ($table) {
            $table->integer('tipo_nota_id');
        });*/
        Schema::table('empleados', function ($table) {
            $table->integer('salario')->change();
        });
        Schema::table('pago_salario', function ($table) {
            $table->integer('salario_pagado')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
