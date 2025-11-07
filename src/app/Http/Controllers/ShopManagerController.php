<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ShopManagerRequest;



class ShopManagerController extends Controller
{
    // ショップ管理ダッシュボード表示
    public function index()
    {
        $shopId = Auth::user()->shop->id;
        $reservations = Reservation::where('shop_id', $shopId)
            ->orderBy('reservation_datetime', 'desc')
            ->paginate(5);

        return view('shop_manager.dashboard', ['reservations' => $reservations]);
    }

    //店舗情報の取得して表示
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('shop_manager.manage-shop', compact('shop'));
    }

    //店舗情報の更新処理
    public function update(ShopManagerRequest $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $data = [
            'description' => $request->description,
            'open_time' => $request->open_time . ':00',
            'close_time' => $request->close_time . ':00',
        ];

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
                $paymentStatusJa = '金額未設定';
                break;
            case 'amount_set':
                $paymentStatusJa = '金額設定済み（支払い待ち）';
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

    public function updatePrice(Request $request, $id)
    {
        try {
            // デバッグ情報
            Log::info('金額設定リクエスト', [
                'id' => $id,
                'request_data' => $request->all(),
                'shop_id' => Auth::user()->shop->id
            ]);

            $reservation = Reservation::where('id', $id)
                ->where('shop_id', Auth::user()->shop->id)
                ->firstOrFail();

            // 予約データのデバッグ
            Log::info('取得した予約データ', [
                'reservation_id' => $reservation->id,
                'shop_id' => $reservation->shop_id,
                'current_amount' => $reservation->total_amount,
                'current_status' => $reservation->payment_status
            ]);

            $request->validate([
                'total_amount' => 'required|integer|min:0',
            ]);

            // 更新データのデバッグ
            Log::info('更新する予約データ', [
                'new_amount' => $request->total_amount,
                'new_status' => 'amount_set'
            ]);

            $result = $reservation->update([
                'total_amount' => $request->total_amount,
                'payment_status' => 'amount_set'
            ]);

            // 更新結果のデバッグ
            Log::info('更新結果', ['result' => $result]);

            return redirect()->route('shop_manager.dashboard')
                ->with('success', '予約金額を設定しました');
        } catch (\Exception $e) {
            // エラーの詳細をログに記録
            Log::error('予約金額設定エラー: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('shop_manager.dashboard')
                ->with('error', '予約金額の設定に失敗しました: ' . $e->getMessage());
        }
    }
}