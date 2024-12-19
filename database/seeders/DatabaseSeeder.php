<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        // Then, insert new roles
        Role::insert([
            ['name' => 'customer'],
            ['name' => 'user'],
            ['name' => 'admin'],
        ]);

    }
}
