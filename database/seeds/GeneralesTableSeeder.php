<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class GeneralesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('generales')->insert([
            'nombre'=>'Organización',
            'valor'=>'',
            'descripcion'=>'Nombre de la organización de la aplicación.',
        ]);
        DB::table('generales')->insert([
            'nombre'=>'Logo',
            'valor'=>'',
            'descripcion'=>'Ruta URL donde se encuentra el logo o escudo .',
        ]);
        DB::table('generales')->insert([
            'nombre'=>'Servidor principal',
            'valor'=>'',
            'descripcion'=>'Campo que solo se usa en caso de que esta aplicación dependa de un servidor maestro. Solo se llena en caso de que esta aplicación sea esclava.',
        ]);
        DB::table('generales')->insert([
            'nombre'=>'Serial',
            'valor'=>'',
            'descripcion'=>'Código serial del dispositivo donde está alojada la aplicación.',
        ]);
    }
}
