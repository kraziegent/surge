<?php

namespace Database\Seeders;

use App\User;
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
        \App\Models\User::factory(10)->create();
        // factory(User::class)->create([
        //     'email' => 'dev@surge.com'
        // ]);
    }
}
