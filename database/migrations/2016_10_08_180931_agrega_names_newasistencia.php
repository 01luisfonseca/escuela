<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregaNamesNewasistencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newasistencia', function (Blueprint $table) {
            $table->string('name');
            $table->string('lastname');
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
            $table->string('name');
            $table->string('lastname');
        });
    }
}
