<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(CarrerasTableSeeder::class);
        $this->call(AlumnosTableSeeder::class);
        $this->call(PeriodosTableSeeder::class);
        $this->call(EtapasTableSeeder::class);
        $this->call(CiclosTableSeeder::class);
        $this->call(TipoMatriculasTableSeeder::class);
        $this->call(TipoPagosTableSeeder::class);
        $this->call(MatriculasTableSeeder::class);
        $this->call(PagosTableSeeder::class);
        $this->call(SeguimientosTableSeeder::class);
    }
}
