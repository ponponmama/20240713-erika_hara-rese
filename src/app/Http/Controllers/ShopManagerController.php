<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;


class ShopManagerController extends Controller
{
    // ショップ管理ダッシュボード表示
    public function index()
    {
        $shopId = Auth::user()->shop->id;  // ユーザーが管理する店舗ID
        $reservations = Reservation::where('shop_id', $shopId)->get();

        //dd($shopId, $reservations);

        return view('shop_manager.dashboard', ['reservations' => $reservations]);
    }

    //店舗情報の取得して表示
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('shop_manager.edit', compact('shop'));
    }

    //店舗情報の更新処理
    public function update(Request $request, $id)
    {
    $request->validate([
        'description' => 'required|string',
        'open_time' => 'required|date_format:H:i',
        'close_time' => 'required|date_format:H:i',
        'image' => 'sometimes|file|image|max:5000',
    ]);

    $shop = Shop::findOrFail($id);
    $data = [
        'description' => $request->description,
        'open_time' => $request->open_time,
        'close_time' => $request->close_time,
    ];

    if ($request->hasFile('image') && $request->file('image')->isValid()) {
        $path = $request->image->store('public/images');
        $data['image'] = $path;
    }

    $shop->update($data);

    return redirect()->route('shop_manager.dashboard')->with('success', '店舗情報が更新されました。');
}


    // 管理店舗
    public function manageShop()
    {
        $shop = Shop::where('manager_id', auth()->id())->first();

        return view('shop_manager.manage-my-shop', compact('shop'));
    }

    public function showReservations()
    {
        // ログインしているユーザーが管理する店舗のIDを取得
        $shopId = Auth::user()->shop_id;  // この部分は適宜調整が必要です

        // その店舗の予約情報を取得
        $reservations = Reservation::where('shop_id', $shopId)->get();

        return view('shop_manager.reservations', ['reservations' => $reservations]);
    }

}
