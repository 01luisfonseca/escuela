<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregaDeviceNewasistencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newasistencia', function (Blueprint $table) {
            $table->integer('authdevice_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newasistencia', function (Blueprint $table) {
            $table->integer('authdevice_id');
        });
    }
}
