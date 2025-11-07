<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;
use App\Http\Requests\StoreReservationRequest;
use Carbon\Carbon;
use App\Services\ShopService;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationNotification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use App\Mail\ReservationUpdated;


class ReservationController extends Controller
{
    protected $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //detailページからのデータを使用して予約保存し予約完了ページへリダイレクト
    public function store(StoreReservationRequest $request)
    {
        Log::info('Store method called');// メソッドの呼び出しをログに記録
        $current = Carbon::now(); // 現在の日時を取得して $current に代入

        // リクエストから店舗IDを取得し、店舗情報を検索
        $shop = Shop::find($request->shop_id);
        // リクエストから取得した日付と時間を組み合わせてCarbonオブジェクトを生成
        $reservationDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

        // 新しい予約を作成し、データベースに保存
        $reservation = new Reservation();
        $reservation->shop_id = $request->shop_id;
        $reservation->reservation_datetime = $reservationDateTime->format('Y-m-d H:i:s');
        $reservation->number = $request->number;
        $reservation->user_id = auth()->id();
        $reservation->total_amount = 0; // 初期値を0円に設定
        $reservation->payment_status = 'pending'; // 支払い状態は「金額未設定」

        $reservation->save();

        // QRコードを生成し、指定のパスにファイルとして保存
        $qrCodePath = 'storage/qr_codes/' . $reservation->id . '.svg'; // 保存パスを指定
        QrCode::format('svg')->size(100)->generate('Reservation ID: ' . $reservation->id, storage_path('app/public/qr_codes/' . $reservation->id . '.svg'));

        $reservation->qr_code = $qrCodePath;
        $reservation->save(); // QRコードパスを更新して再保存

        // この処理では、現在認証されているユーザーのメールアドレスに対して、予約の詳細を含むメールを送信します。
        $user = auth()->user(); // ログインしているユーザー情報を取得
        if ($user) {
            Mail::to($user->email)->send(new ReservationNotification($user, $reservation));
        } else {
            // 送信に失敗した場合は、エラーログにその情報を記録します。
            Log::error('User not found for email sending.');
        }

        // 予約情報をセッションに保存
        session()->put('reservation_details', $reservation);
        session()->put('last_visited_shop_id', $reservation->shop_id);

        // 予約完了ページにリダイレクト
        return redirect()->route('reservation.done');
    }

    // 予約完了ページ
    public function done()
    {
        return view('done');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*詳しく見るクリックし店舗の詳細表示と予約フォーム表示用*/
    public function show($id)
    {
        $shop = Shop::findOrFail($id);
        $current = Carbon::now();
        $date = $current->format('Y-m-d');
        $end = new Carbon($date . ' ' . $shop->close_time);

        // 現在の時間が営業終了時間を過ぎているかチェック
        if ($current->greaterThanOrEqualTo($end)) {
            // 営業時間を過ぎている場合、日付を次の日に設定
            $date = $current->copy()->addDay()->format('Y-m-d');
        }

        // 営業時間の取得
        $times = $this->shopService->getBusinessHours($shop->open_time, $shop->close_time, $date, $current);
        $reservation = Reservation::where('shop_id', $id)->latest()->first();

        return view('shops.detail', [
            'shop' => $shop,
            'date' => $date,
            'times' => $times,
            'reservation' => $reservation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //マイページで予約の日時変更と削除
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d|after_or_equal:' . Carbon::today()->toDateString(),
            'time' => 'required|date_format:H:i',
            'number' => 'required|integer|min:1'
        ]);

        $shop = Shop::find($request->shop_id);
        $reservationDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

        $reservation = Reservation::findOrFail($id);
        $reservation->reservation_datetime = $reservationDateTime->format('Y-m-d H:i:s');
        $reservation->number = $request->number;
        $reservation->save();

        // QRコードを再生成（必要に応じて）
        $qrCodePath = 'storage/qr_codes/' . $reservation->id . '.svg'; // 保存パスを指定
        QrCode::format('svg')->size(100)->generate('Reservation ID: ' . $reservation->id, storage_path('app/public/qr_codes/' . $reservation->id . '.svg'));

        $reservation->qr_code = $qrCodePath;
        $reservation->save();

        $user = auth()->user(); // ログインしているユーザー情報を取得
        if ($user) {
            Mail::to($user->email)->send(new ReservationUpdated ($user, $reservation));
        } else {
            Log::error('User not found for email sending.');
        }

        return redirect()->route('mypage')->with('reservation_success', '予約が更新されました。');
    }

    /**
     * myページにユーザーの予約一覧を表示するメソッド。
     *
     * @return \Illuminate\Http\Response
     */
    public function myReservations()
    {
        $user_id = auth()->id(); // ログインユーザーのIDを取得
        $reservations = Reservation::where('user_id', $user_id)->with('shop')->get(); // ユーザーの予約と関連する店舗情報を取得

        return view('reservations.my', ['reservations' => $reservations]); // ビューにデータを渡す
    }

    //マイページで予約の削除処理
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return redirect()->route('mypage')->with('reservation_success', '予約が削除されました。');
    }

    //予約idをQRコードで取得し予約情報を検索し、JSON形式で返す。予約ID、予約日時、人数、顧客名、顧客のメールアドレスを含む
    public function getReservationById($id)
    {
        $reservation = Reservation::with('user')->findOrFail($id);
        return response()->json([
            'id' => $reservation->id,
            'reservation_datetime' => $reservation->reservation_datetime->format('Y-m-d H:i'),
            'number' => $reservation->number,
            'user_name' => $reservation->user->user_name,
            'email' => $reservation->user->email
        ]);
    }

    /*ゲストユーザー(未登録)が詳しく見るボタンをクリックした時の処理*/
    public function shopDetailsOrChoose($id)
    {
        if (auth()->check()) {
            return $this->show($id);
        } else {
            return redirect()->route('choose');
        }
    }

    // done.blade.phpからdetail.blade.phpに戻った時用の専用メソッド
    public function returnFromDone($id)
    {
        $shop = Shop::findOrFail($id);
        $current = Carbon::now();

        // セッションから予約情報を取得
        $reservationDetails = session('reservation_details');

        // 日付の取得（セッションの日付、または現在の日付）
        $date = session('selected_date', $current->format('Y-m-d'));
        $end = new Carbon($date . ' ' . $shop->close_time);

        Log::info("Current time: " . $current->toDateTimeString()); // 現在の時間をログに記録
        Log::info("End time: " . $end->toDateTimeString()); // 営業終了時間をログに記録

        // 現在の時間が営業終了時間を過ぎているかチェック
        if ($current->greaterThanOrEqualTo($end)) {
            // 営業時間を過ぎている場合、日付を次の日に設定
            $date = $current->copy()->addDay()->format('Y-m-d');
            // セッションの日付も更新
            session(['selected_date' => $date]);
        }

        Log::info("New date: " . $date); // 更新された日付をログに記録

        // 営業時間の取得
        $times = $this->shopService->getBusinessHours($shop->open_time, $shop->close_time, $date, $current);

        $reservation = Reservation::where('shop_id', $id)->latest()->first();

        // ユーザーが他のページに遷移する際にセッションデータをクリアするためのフラグを設定
        session()->put('clear_session_on_leave', true);
        Log::info('Session clear flag set in returnFromDone');

        return view('shops.detail', [
            'shop' => $shop,
            'date' => $date,
            'times' => $times,
            'reservation' => $reservation,
            'reservationDetails' => $reservationDetails,
        ]);
    }
}