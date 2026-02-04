<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $adminUser = User::updateOrCreate(
        ['email' => 'admin@test.com'],    
        [
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password'=>'admin@123'
        ]);

        $normalUser = User::updateOrCreate(
        ['email' => 'user@test.com'],    
        [
            'name' => 'user',
            'email' => 'user@test.com',
            'password'=>'user@123'
        ]);

        $adminUser->assignRole('Admin');
        $normalUser->assignRole('User');
        
    }
}
