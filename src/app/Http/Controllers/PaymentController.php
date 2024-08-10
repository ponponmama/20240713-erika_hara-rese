<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    public function showForm()
    {
        return view('payment.form');
    }

    public function processPayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $charge = Charge::create([
                'amount' => 100, // 金額
                'currency' => 'jpy', // 通貨コードは小文字で 'jpy'
                'description' => 'テスト',
                'source' => $request->stripeToken,
            ]);

            return back()->with('success_message', '支払い完了!');
        } catch (\Exception $e) {
            return back()->with('error_message', 'Error: ' . $e->getMessage());
        }
    }
}