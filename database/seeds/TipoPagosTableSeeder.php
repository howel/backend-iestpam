<?php

use Illuminate\Database\Seeder;

class TipoPagosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_pagos')->insert([
            'tipo_matriculas_id' => 1,
            'nombre' => 'PAGO UNICO',
            'importe' => 220,
            'partes' => 1,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('tipo_pagos')->insert([
            'tipo_matriculas_id' => 2,
            'nombre' => 'PAGO UNICO',
            'importe' => 240,
            'partes' => 1,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('tipo_pagos')->insert([
            'tipo_matriculas_id' => 2,
            'nombre' => 'PAGO EN DOS PARTES',
            'importe' => 240,
            'partes' => 2,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
