<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    //支払いページの表示
    public function showForm(Request $request)
    {
        // 予約情報の取得と検証
        $reservation = Reservation::findOrFail($request->reservation_id);

        // 予約が未払いか確認
        if ($reservation->payment_status === 'completed') {
            return redirect()->route('reservations.show', $reservation->id)
                ->with('error_message', 'この予約は既に支払い済みです。');
        }

        return view('payment.form', compact('reservation'));
    }

    //Stripeを使用した支払い処理
    public function processPayment(Request $request)
    {
        // 予約情報の取得と検証
        $reservation = Reservation::findOrFail($request->reservation_id);

        // 予約が未払いか確認
        if ($reservation->payment_status === 'completed') {
            return redirect()->route('reservations.show', $reservation->id)
                ->with('error_message', 'この予約は既に支払い済みです。');
        }

        // Stripe APIキーの設定
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // 支払いの実行
            $charge = Charge::create([
                'amount' => $reservation->total_amount * 100, // 金額をセント単位に変換
                'currency' => 'jpy',
                'description' => '予約番号: ' . $reservation->id,
                'source' => $request->stripeToken,
            ]);

            // 支払い情報の保存
            Payment::create([
                'user_id' => auth()->id(),
                'reservation_id' => $reservation->id,
                'total_payment_amount' => $reservation->total_amount,
                'payment_status' => 'completed',
            ]);

            // 予約状態の更新
            $reservation->update(['payment_status' => 'completed']);

            return redirect()->route('reservations.show', $reservation->id)
                ->with('success_message', '支払いが完了しました。');
        } catch (\Exception $e) {
            // 支払い失敗時の処理
            Payment::create([
                'user_id' => auth()->id(),
                'reservation_id' => $reservation->id,
                'total_payment_amount' => $reservation->total_amount,
                'payment_status' => 'failed',
            ]);

            return back()->with('error_message', '支払い処理中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}