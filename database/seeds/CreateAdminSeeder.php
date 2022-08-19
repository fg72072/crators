<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'phone' => '03497575999',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('super-admin');

        $users = User::create([
            'name' => 'doctor',
            'email' => 'doctor@gmail.com',
            'username' => 'doctor',
            'phone' => '03497575999',
            'password' => Hash::make('12345678'),
        ]);

        $users->assignRole('doctor');

        $users = User::create([
            'name' => 'lab',
            'email' => 'lab@gmail.com',
            'username' => 'lab',
            'phone' => '03497575999',
            'password' => Hash::make('12345678'),
        ]);

        $users->assignRole('lab');

        $users = User::create([
            'name' => 'wholesaler',
            'email' => 'wholesaler@gmail.com',
            'username' => 'wholesaler',
            'phone' => '03497575999',
            'password' => Hash::make('12345678'),
        ]);

        $users->assignRole('wholesaler');

        
        $users = User::create([
            'name' => 'pharmacy',
            'email' => 'pharmacy@gmail.com',
            'username' => 'pharmacy',
            'phone' => '03497575999',
            'password' => Hash::make('12345678'),
        ]);

        $users->assignRole('pharmacy');

        $users = User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'username' => 'user',
            'phone' => '03497575999',
            'password' => Hash::make('12345678'),
        ]);

        $users->assignRole('user');
    }
}
