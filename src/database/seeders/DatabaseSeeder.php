<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Seeders\ShopsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->admin(1)->create();

        User::factory()->count(25)->create();

        $this->call([
            ShopsTableSeeder::class,
            ReservationSeeder::class,
        ]);
        //\App\Models\User::factory(20)->create();
    }
}

