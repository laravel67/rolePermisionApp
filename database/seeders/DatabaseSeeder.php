<?php

namespace Database\Seeders;

use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create();
        Post::factory(50)->create();

        $this->call([PermissionTableSeeder::class, RoleTableSeeder::class, UserTableSeeder::class]);
    }
}
