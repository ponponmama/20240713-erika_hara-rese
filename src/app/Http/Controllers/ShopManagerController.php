<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class ShopManagerController extends Controller
{
    // ショップ管理ダッシュボード表示
    public function index()
    {
        $shopId = Auth::user()->shop->id;
        $reservations = Reservation::where('shop_id', $shopId)->get();

        return view('shop_manager.dashboard', ['reservations' => $reservations]);
    }

    //店舗情報の取得して表示
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('shop_manager.manage-shop', compact('shop'));
    }

    //店舗情報の更新処理
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
            'image' => 'sometimes|file|image|max:5000',
            'price' => 'required|integer|min:0',
        ]);

        $shop = Shop::findOrFail($id);
        $data = [
            'description' => $request->description,
            'open_time' => $request->open_time. ':00',
            'close_time' => $request->close_time. ':00',
            'price' => $request->price,
        ];

        Log::info('Open Time:', ['open_time' => $request->open_time]);
        Log::info('Close Time:', ['close_time' => $request->close_time]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->image->store('images', 'public');
            $data['image'] = $path;
        }

        $shop->update($data);

        return redirect()->route('shop_manager.edit', $id)->with('success', '店舗情報が更新されました。');
    }


    // 管理店舗
    public function manageShop()
    {
        // auth()->id() で現在認証されているユーザーのIDを取得し、そのIDを持つ店舗を検索
        $shop = Shop::where('user_id', auth()->id())->firstOrFail();

        return view('shop_manager.manage-shop', compact('shop'));

    }

    public function showReservations()
    {
        // ログインしているユーザーが管理する店舗のIDを取得
        $shopId = Auth::user()->shop_id;

        // その店舗の予約情報を取得
        $reservations = Reservation::where('shop_id', $shopId)->get();

        return view('shop_manager.reservations', ['reservations' => $reservations]);
    }

    // 予約詳細を取得するためのメソッド（モーダル表示用）
    public function getReservationDetails($id)
    {
        $shopId = Auth::user()->shop->id;
        $reservation = Reservation::where('id', $id)
                                ->where('shop_id', $shopId)
                                ->with('user')
                                ->first();

        if (!$reservation) {
            return response()->json(['error' => '予約が見つかりませんでした。'], 404);
        }

        // 支払い状態を日本語に変換
        $paymentStatus = $reservation->payment_status;
        $paymentStatusJa = '';
        switch ($paymentStatus) {
            case 'pending':
                $paymentStatusJa = '決済手続き中';
                break;
            case 'completed':
                $paymentStatusJa = '決済完了';
                break;
            case 'failed':
                $paymentStatusJa = '決済失敗';
                break;
            default:
                $paymentStatusJa = $paymentStatus;
        }

        $data = [
            'id' => $reservation->id,
            'reservation_datetime' => $reservation->reservation_datetime->format('Y-m-d'),
            'time' => $reservation->reservation_datetime->format('H:i'),
            'number' => $reservation->number,
            'user_name' => $reservation->user->user_name,
            'email' => $reservation->user->email,
            'payment_status' => $paymentStatusJa,
            'total_amount' => $reservation->total_amount,
        ];

        return response()->json($data);
    }

    // サーバーサイドで動作し、予約情報を照合、該当する予約詳細を表示
    //ログイン済み店舗管理者の店舗idと照合した予約idを元に予約情報を検索、予約詳細表示。存在しない場合はエラーメッセージを表示。
    public function verifyReservation($reservationId)
    {
        $shopId = Auth::user()->shop->id;
        $reservation = Reservation::where('id', $reservationId)
                                ->where('shop_id', $shopId)
                                ->first();

        if (!$reservation) {
            return back()->with('error', '予約が見つかりません。');
        }

        return view('shop_manager.reservation_details', compact('reservation'));
    }

    public function updatePrice(Request $request)
    {
        try {
            $shop = Auth::user()->shop;
            $shop->price = $request->price;
            $shop->save();

            return redirect()->route('shop.dashboard')
                ->with('success', '予約金額を更新しました');
        } catch (\Exception $e) {
            return redirect()->route('shop.dashboard')
                ->with('error', '予約金額の更新に失敗しました');
        }
    }
}