<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'jenis_kelamin' => 'Laki-laki',
        ]);

        User::create([
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('user123'),
            'jenis_kelamin' => 'Perempuan',
        ]);
    }
}