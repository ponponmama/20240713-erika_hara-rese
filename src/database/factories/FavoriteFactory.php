<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition()
    {
        $users = User::where('role', 3)->get();
        $shops = Shop::all();
        $existingFavorites = Favorite::all()->map(function ($favorite) {
            return $favorite->user_id . '-' . $favorite->shop_id;
        })->toArray();

        do {
            $user = $users->random();
            $shop = $shops->random();
            $key = $user->id . '-' . $shop->id;
        } while (in_array($key, $existingFavorites));

        return [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ];
    }
}
