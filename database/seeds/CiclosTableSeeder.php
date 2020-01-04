<?php

use Illuminate\Database\Seeder;

class CiclosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ciclos')->insert([
            'etapas_id' =>  1,
            'nombre' =>  1,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('ciclos')->insert([
            'etapas_id' =>  1,
            'nombre' =>  3,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('ciclos')->insert([
            'etapas_id' =>  1,
            'nombre' =>  5,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('ciclos')->insert([
            'etapas_id' =>  2,
            'nombre' =>  2,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('ciclos')->insert([
            'etapas_id' =>  2,
            'nombre' =>  4,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('ciclos')->insert([
            'etapas_id' =>  2,
            'nombre' =>  6,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
