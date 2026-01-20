<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    //支払いページの表示
    public function showForm(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // 一般ユーザー（role 3）のみ支払いページ表示可能
        if ($user->role !== 3) {
            return back()->with('error_message', '支払いは一般ユーザーのみ利用可能です。');
        }

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
        /** @var User $user */
        $user = Auth::user();

        // 一般ユーザー（role 3）のみ支払い処理可能
        if ($user->role !== 3) {
            return back()->with('error_message', '支払いは一般ユーザーのみ利用可能です。');
        }

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
                'amount' => $reservation->total_amount, // 日本円の場合はセント単位への変換は不要
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

            return redirect()->route('mypage')
                ->with('success_message', '支払いが完了しました。');
        } catch (\Exception $e) {
            // 支払い失敗時の処理
            Payment::create([
                'user_id' => auth()->id(),
                'reservation_id' => $reservation->id,
                'total_payment_amount' => $reservation->total_amount,
                'payment_status' => 'failed',
            ]);

            // 予約状態を決済失敗に更新
            $reservation->update(['payment_status' => 'failed']);

            return back()->with('error_message', '支払い処理中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}
