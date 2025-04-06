<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // 既存の予約に対して支払いデータを作成
        Reservation::where('payment_status', 'pending')->each(function ($reservation) {
            Payment::factory()->create([
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'total_payment_amount' => $reservation->total_amount,
                'payment_status' => 'pending',
            ]);
        });

        // 支払い済みの予約に対して支払いデータを作成
        Reservation::where('payment_status', 'completed')->each(function ($reservation) {
            Payment::factory()->create([
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'total_payment_amount' => $reservation->total_amount,
                'payment_status' => 'completed',
            ]);
        });

        // 支払い失敗の予約に対して支払いデータを作成
        Reservation::where('payment_status', 'failed')->each(function ($reservation) {
            Payment::factory()->create([
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'total_payment_amount' => $reservation->total_amount,
                'payment_status' => 'failed',
            ]);
        });

        // 追加のダミーデータを作成
        Payment::factory(10)->create();
    }
}
