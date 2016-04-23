<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);
        DB::table('tipo_usuario')->insert([
            'nombre_tipo'=>'Ninguno',
            ]);
        DB::table('tipo_usuario')->insert([
            'nombre_tipo'=>'Alumno',
            ]);
        DB::table('tipo_usuario')->insert([
            'nombre_tipo'=>'Trabajador estándar',
            ]);
        DB::table('tipo_usuario')->insert([
            'nombre_tipo'=>'Profesor',
            ]);
        DB::table('tipo_usuario')->insert([
            'nombre_tipo'=>'Coordinador',
            ]);
        DB::table('tipo_usuario')->insert([
            'nombre_tipo'=>'Administrador',
            ]);
        DB::table('users')->insert([
            'name'=>'administrador',
            'identificacion'=>'1',
            'tipo_usuario_id'=>'6',
            'email'=>'01luisfonseca@gmail.com',
            'password'=>bcrypt('admin1234'),
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Enero',
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Febrero',
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Marzo',
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Abril',
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Mayo',
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Junio',
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Julio',
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Agosto',
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Septiembre',
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Octubre',
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Noviembre',
            ]);
        DB::table('mes')->insert([
            'nombre_mes'=>'Diciembre',
            ]);

        Model::reguard();
    }
}
