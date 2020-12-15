<?php

namespace Database\Seeders;

use Database\Seeders\CategorySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // Db must be seeded in this order : 1-roles, 2-users, 3-categories & tags
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(TagSeeder::class);
    }
}
