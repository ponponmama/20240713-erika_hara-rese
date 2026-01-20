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
        // pending（未決済）以外の予約を取得（金額が設定されている予約のみ）
        $reservation = Reservation::whereIn('payment_status', ['amount_set', 'completed', 'failed'])
            ->inRandomOrder()
            ->first();

        // 予約が存在しない場合はデフォルト値を返す
        if (!$reservation) {
            return [
                'user_id' => User::where('role', 3)->inRandomOrder()->first()->id ?? 1,
                'reservation_id' => 1,
                'total_payment_amount' => 0,
                'payment_status' => 'pending',
                'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            ];
        }

        // 予約の支払い状態に応じてPaymentの支払い状態を決定
        $paymentStatus = 'pending';
        if ($reservation->payment_status === 'completed') {
            $paymentStatus = 'completed';
        } elseif ($reservation->payment_status === 'failed') {
            $paymentStatus = 'failed';
        }

        return [
            'user_id' => $reservation->user_id,
            'reservation_id' => $reservation->id,
            'total_payment_amount' => $reservation->total_amount, // 予約の金額を使用
            'payment_status' => $paymentStatus,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
