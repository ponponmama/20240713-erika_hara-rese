<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\Payment;
use App\Models\Shop;
use App\Models\Admin;
use Database\Seeders\ShopsTableSeeder;
use Database\Seeders\PaymentSeeder;

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
        $adminUser = User::factory()->admin()->create();
        User::factory()->count(50)->create(); // 一般ユーザーを50人に増やす

        // ショップと予約をシード
        $this->call([
            ShopsTableSeeder::class,
            ReservationSeeder::class,
        ]);

        // adminユーザーとショップの関連付け
        $shops = Shop::all();
        foreach ($shops as $shop) {
            Admin::create([
                'user_id' => $adminUser->id,
                'shop_id' => $shop->id,
            ]);
        }

        // お気に入りをシード（重複を防ぐため、ユーザーごとにランダムな数のショップをお気に入りに）
        $users = User::where('role', 3)->get();
        $shops = Shop::all();

        foreach ($users as $user) {
            $favoriteCount = rand(1, 5); // 各ユーザーが1-5件のお気に入りを持つ
            $randomShops = $shops->random($favoriteCount);

            foreach ($randomShops as $shop) {
                Favorite::create([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                ]);
            }
        }

        // レビューをシード
        Review::factory()->count(100)->create();

        // 支払い情報をシード
        $this->call([
            PaymentSeeder::class,
        ]);
    }
}