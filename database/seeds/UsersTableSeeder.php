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
    { if(!User::where('email', 'admin@admin.com')->first()){
        User::firstOrCreate([
            'name'      => 'Administrador',
            'email'     => 'admin@admin.com',
            'role'      => 'super_admin',
            'password'  => bcrypt('admin')
        ]);
    }
    }
}
