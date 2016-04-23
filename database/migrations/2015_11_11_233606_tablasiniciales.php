<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tablasiniciales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('users')) {
        Schema::create('users', function (Blueprint $table) {
            //Creacion de columnas
            $table->increments('id')->unique();
            $table->string('name');
            $table->string('lastname');
            $table->biginteger('identificacion');
            $table->date('birday');
            $table->string('email');
            $table->string('password', 60);
            $table->string('telefono');
            $table->string('direccion');
            $table->string('acudiente');
            $table->string('tipo_sangre');
            $table->integer('tipo_usuario_id');
            $table->rememberToken();
            $table->timestamps();
            
        });
        }
        if (!Schema::hasTable('tipo_usuario')) {
        Schema::create('tipo_usuario', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('nombre_tipo');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('niveles')) {
        Schema::create('niveles', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('nombre_nivel');
            $table->mediumText('descripcion');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('materias')) {
        Schema::create('materias', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('nombre_materia');
            $table->mediumText('descripcion');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('periodos')) {
        Schema::create('periodos', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('nombre_periodo');
            $table->mediumText('descripcion');
            $table->date('fecha_ini');
            $table->date('fecha_fin');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('asistencia')) {
        Schema::create('asistencia', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->date('fecha');
            $table->integer('niveles_has_periodos_id');
            $table->integer('alumnos_id');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('notas')) {
        Schema::create('notas', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->string('nombre_nota');
            $table->mediumText('descripcion');
            $table->integer('niveles_has_periodos_id');
            $table->integer('alumnos_id');
            $table->float('calificacion');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('niveles_has_periodos')) {
        Schema::create('niveles_has_periodos', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->integer('materias_has_niveles_id');
            $table->integer('periodos_id');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('materias_has_niveles')) {
        Schema::create('materias_has_niveles', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->integer('empleados_id');
            $table->integer('niveles_id');
            $table->integer('materias_id');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('precio_pension')) {
        Schema::create('precio_pension', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->integer('niveles_id');
            $table->float('valor');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('precio_matricula')) {
        Schema::create('precio_matricula', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->integer('niveles_id');
            $table->float('valor');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('alumnos')) {
        Schema::create('alumnos', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->integer('users_id');
            $table->integer('niveles_id');
            $table->float('pension');
            $table->mediumText('descripcion_pen');
            $table->float('matricula');
            $table->mediumText('descripcion_mat');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('pago_matricula')) {
        Schema::create('pago_matricula', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->text('numero_factura');
            $table->integer('alumnos_id');
            $table->float('valor');
            $table->float('faltante');
            $table->mediumText('descripcion');
            $table->timestamp('cancelado_at');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('pago_pension')) {
        Schema::create('pago_pension', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->text('numero_factura');
            $table->integer('alumnos_id');
            $table->integer('mes_id');
            $table->float('valor');
            $table->float('faltante');
            $table->mediumText('descripcion');
            $table->timestamp('cancelado_at');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('empleados')) {
        Schema::create('empleados', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->integer('users_id');
            $table->float('salario');
            $table->string('eps');
            $table->float('eps_val');
            $table->string('arl');
            $table->float('arl_val');
            $table->string('pension');          
            $table->float('pension_val');
            $table->date('contrato_ini');
            $table->date('contrato_fin');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('pago_salario')) {
        Schema::create('pago_salario', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->date('fecha_ini');
            $table->date('fecha_fin');
            $table->integer('anio');
            $table->integer('mes_id');
            $table->smallInteger('dias');
            $table->integer('empleados_id');
            $table->float('salario_pagado');
            $table->float('auxmovil');
            $table->float('eps_empleado');
            $table->float('eps_empresa');
            $table->float('pension_empleado');
            $table->float('pension_empresa');
            $table->float('arl_empresa');
            $table->float('descuento');
            $table->mediumText('descripcion_desc');
            $table->float('bonificacion');
            $table->mediumText('descripcion_boni');
            $table->mediumText('notas');
            $table->timestamp('pagado_at');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('mes')) {
        Schema::create('mes', function (Blueprint $table) {
            //Tablas
            $table->increments('id')->unique();
            $table->text('nombre_mes');
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('tipo_usuario');
        Schema::dropIfExists('niveles');
        Schema::dropIfExists('materias');
        Schema::dropIfExists('periodos');
        Schema::dropIfExists('asistencia');
        Schema::dropIfExists('notas');
        Schema::dropIfExists('niveles_has_periodos');
        Schema::dropIfExists('materias_has_niveles');
        Schema::dropIfExists('precio_pension');
        Schema::dropIfExists('precio_matricula');
        Schema::dropIfExists('alumnos');
        Schema::dropIfExists('pago_matricula');
        Schema::dropIfExists('pago_pension');
        Schema::dropIfExists('empleados');
        Schema::dropIfExists('pago_salario');
        Schema::dropIfExists('mes');
    }
}
