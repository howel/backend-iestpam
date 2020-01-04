<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios')->insert([
            'dni' => 75316518,
            'nombres' => 'Admin',
            'apellidos' => 'Super',
            'email' => 'super@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$9EzgeMLnru8mCfzapP9i.e96hMNx1Z0v1g/YXW9Mw06IDrAfw8/uG', // secret
            'remember_token' => str_random(10),
            'src_img' => null,
            'perfil' => 'DEVELOPER',
            'estado' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('usuarios')->insert([
            'dni' => '00813931',
            'nombres' => 'ISFER',
            'apellidos' => 'ADMIN',
            'email' => 'admin@isfer.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$9EzgeMLnru8mCfzapP9i.e96hMNx1Z0v1g/YXW9Mw06IDrAfw8/uG', // secret
            'remember_token' => str_random(10),
            'src_img' => null,
            'perfil' => 'ADMINISTRADOR(A)',
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
