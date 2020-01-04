<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EtapasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('etapas')->insert([
            'inicio' => Carbon::now(),
            'final' => Carbon::now()->addDays(70),
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('etapas')->insert([
            'inicio' => Carbon::now()->addDays(80),
            'final' => Carbon::now()->addDays(130),
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
