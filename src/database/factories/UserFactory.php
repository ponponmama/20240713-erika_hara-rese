<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make('user_pass'),
            'role' => 3,
        ];
    }

    public function shopManager($shopId)
    {
        return $this->state(function (array $attributes) use ($shopId)  {
            return [
                'user_name' => "test{$shopId}",
                'email' => "test{$shopId}@test.com",
                'role' => 2,
                'password' => Hash::make('shop_pass')  // テスト用の既知のパスワード
            ];
        });
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 1,
                'password' => Hash::make('admin_pass')  // テスト用の既知のパスワード
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
