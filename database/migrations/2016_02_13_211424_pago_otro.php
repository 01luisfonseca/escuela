<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PagoOtro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('pago_otro')) {
        Schema::create('pago_otro', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->text('numero_factura');
            $table->integer('alumnos_id');
            $table->float('valor');
            $table->mediumText('descripcion');
            $table->timestamp('cancelado_at');
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
        Schema::dropIfExists('pago_otro');
    }
}
