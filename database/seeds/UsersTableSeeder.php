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
            'name'      => 'Administrador',
            'email'     => 'luisfj_pr@hotmail.com',
            'role'      => 'super_admin',
            'password'  => bcrypt('xpt467')
        ]);
        /*User::create([
            'name'      => 'Carlos',
            'email'     => 'carlos@gmail.com',
            'password'  => bcrypt('12345678')
        ]);*/
    }
}
