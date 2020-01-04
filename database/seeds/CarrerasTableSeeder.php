<?php

use Illuminate\Database\Seeder;

class CarrerasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('carreras')->insert([
            'nombre' => 'COMPUTACIÓN E INFORMÁTICA',
            'descripcion' => 'COMPUTACIÓN E INFORMÁTICA',
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('carreras')->insert([
            'nombre' => 'ENFERMERÍA TÉCNICA',
            'descripcion' => 'ENFERMERÍA TÉCNICA',
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('carreras')->insert([
            'nombre' => 'PRODUCCIÓN AGROPECUARÍA',
            'descripcion' => 'PRODUCCIÓN AGROPECUARÍA',
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('carreras')->insert([
            'nombre' => 'CONTABILIDAD',
            'descripcion' => 'CONTABILIDAD',
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
