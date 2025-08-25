<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'      => 'Admin User',
            'address'   => '123 Main Street',
            'email'     => 'admin@example.com',
            'birthDay'  => '1990-01-01',
            'contact'   => '1234567890',
            'userType'  => 'Teacher',
            'gender'    => 'Male',
            'location'  => 'Sri Lanka',
            'username'  => 'admin',
            'password'  => Hash::make('pass1234'),
            'photo'     => null,
            'userRole'  => 'admin',
            'status'    => true,
        ]);
    }
}
