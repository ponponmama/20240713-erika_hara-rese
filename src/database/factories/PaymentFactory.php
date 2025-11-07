<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        $reservation = Reservation::inRandomOrder()->first();
        return [
            'user_id' => User::where('role', 3)->inRandomOrder()->first()->id,
            'reservation_id' => $reservation->id,
            'total_payment_amount' => $this->faker->numberBetween(5000, 50000),
            'payment_status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}