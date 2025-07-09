<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat role
        $user = Role::firstOrCreate(['name' => 'user']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        
        // Tambahkan role ke user
        $user1 = User::where('username', 'user')->first();
        if ($user1) {
            $user1->assignRole($user);
        }
        $adminUser = User::where('username', 'admin')->first();
        if ($adminUser) {
            $adminUser->assignRole($admin);
        }
    }
}