<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'      => 'Carlos',
            'email'     => 'carlos@gmail.com',
            'password'  => bcrypt('12345678')
        ]);
        User::create([
            'name'      => 'Administrador',
            'email'     => 'admin@mytradebook.com',
            'password'  => bcrypt('12345678')
        ]);
    }
}
