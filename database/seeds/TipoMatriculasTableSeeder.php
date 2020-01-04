<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TipoMatriculasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_matriculas')->insert([
            'nombre' => 'ORDINARIO',
            'descripcion' => 'ORDINARIO',
            'inicio' => Carbon::now(),
            'final' => Carbon::now()->addDays(15),
            'importe' => 220,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('tipo_matriculas')->insert([
            'nombre' => 'EXTRAORDINARIO',
            'descripcion' => 'EXTRAORDINARIO',
            'inicio' => Carbon::now()->addDays(17),
            'final' => Carbon::now()->addDays(33),
            'importe' => 240,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
