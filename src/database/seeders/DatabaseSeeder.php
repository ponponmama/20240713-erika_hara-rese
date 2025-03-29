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
        // エリアとジャンルを先にシード
        $this->call([
            AreasTableSeeder::class,
            GenresTableSeeder::class,
        ]);

        // ユーザーを作成
        User::factory()->admin(1)->create();
        User::factory()->count(25)->create();

        // ショップと予約をシード
        $this->call([
            ShopsTableSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}