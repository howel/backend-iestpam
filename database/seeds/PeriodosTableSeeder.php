<?php

use Illuminate\Database\Seeder;

class PeriodosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('periodos')->insert([
            'nombre' =>  '2019',
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
