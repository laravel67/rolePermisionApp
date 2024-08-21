<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('name', 'super-admin')->first();

        $user = User::create([
            'name' => 'Murtaki Shihab',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
        ]);

        $user->assignRole($role);
    }
}
