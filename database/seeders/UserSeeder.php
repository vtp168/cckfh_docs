<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          Users::create([
            'firstName' => 'Admin',
            'lastName' => 'User',
            'userName' => 'admin',
            'email' => 'admin@gmail.com',
            'emailConfirmed' => true,
            'phoneNumber' => '0123456789',
            'phoneNumberConfirmed' => true,
            'twoFactorEnabled' => false,
            'lockoutEnabled' => false,
            'accessFailedCount' => 0,
            'password' => Hash::make('password'), // Change this password later
            'isDeleted' => false,
        ]);
    }
}
