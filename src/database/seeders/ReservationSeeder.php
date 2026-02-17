<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Shop;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Faker\Factory as Faker;
use App\Services\ShopService;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    protected $faker;
    protected $shopService;

    public function __construct()
    {
        $this->faker = Faker::create('ja_JP');
        $this->shopService = new ShopService();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 一般ユーザーを取得（user_nameが 'test_user' のユーザー）
        $user = User::where('role', 3)->first();

        if (!$user) {
            throw new \Exception("No general user found");
        }

        // 全てのショップを取得
        $shops = Shop::all();

        // ショップが存在しない場合のエラーハンドリング
        if ($shops->isEmpty()) {
            throw new \Exception("No shops found");
        }

        foreach ($shops as $shop) {
            // 各ショップに対して10件の予約を作成（ランチ5件 + デイナー5件）
            for ($i = 0; $i < 5; $i++) {

                $user = User::where('role', 3)->inRandomOrder()->first();
                if (!$user) {
                    throw new \Exception("No general user found");
                }

                // 予約日を決定
                $reservationDate = now()->addDays(rand(1, 30))->format('Y-m-d');

                // 営業時間を取得（15分間隔）
                $availableTimes = $this->shopService->getBusinessHours($shop->open_time, $shop->close_time, $reservationDate, null);

                // ランチ時間帯（11:00-14:00）から営業時間内の時間を選択
                $lunchTimes = array_filter($availableTimes, function($time) {
                    $hour = (int)explode(':', $time)[0];
                    return $hour >= 11 && $hour < 14;
                });

                // ランチ時間が営業時間内にない場合は、営業開始時間から3時間以内の時間を使用
                if (empty($lunchTimes)) {
                    $openHour = (int)explode(':', $shop->open_time)[0];
                    $lunchTimes = array_filter($availableTimes, function($time) use ($openHour) {
                        $hour = (int)explode(':', $time)[0];
                        return $hour >= $openHour && $hour < $openHour + 3;
                    });
                }

                // ランチ時間がまだない場合は、営業時間内の最初の時間を使用
                if (empty($lunchTimes)) {
                    $lunchTimes = $availableTimes;
                }

                $lunchTime = $this->faker->randomElement(array_values($lunchTimes));
                $lunchDateTime = Carbon::createFromFormat('Y-m-d H:i', $reservationDate . ' ' . $lunchTime);

                // 支払い状態を決定
                $paymentStatus = $this->faker->randomElement(['pending', 'amount_set', 'completed', 'failed']);
                // 未決済（pending）の場合は金額を0、それ以外は金額を設定
                $totalAmount = ($paymentStatus === 'pending') ? 0 : rand(3000, 8000);

                // ランチ予約を作成
                $lunchReservation = Reservation::create([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                    'reservation_datetime' => $lunchDateTime,
                    'number' => rand(1, 10),
                    'total_amount' => $totalAmount,
                    'payment_status' => $paymentStatus
                ]);

                // 予約IDを使用してQRコードを生成
                $lunchQrCodePath = 'qr_codes/' . $lunchReservation->id . '.svg';
                $qrCodeContent = 'Reservation ID: ' . $lunchReservation->id;
                QrCode::format('svg')->size(200)->margin(2)->generate($qrCodeContent, storage_path('app/public/' . $lunchQrCodePath));
                $lunchReservation->qr_code = $lunchQrCodePath;
                $lunchReservation->save();

                $user = User::where('role', 3)->inRandomOrder()->first();
                if (!$user) {
                    throw new \Exception("No general user found");
                }

                // デイナー時間帯（17:00-21:00）から営業時間内の時間を選択
                $dinnerTimes = array_filter($availableTimes, function($time) {
                    $hour = (int)explode(':', $time)[0];
                    return $hour >= 17 && $hour < 21;
                });

                // ディナー時間が営業時間内にない場合は、営業時間の後半（後ろから3時間）を使用
                if (empty($dinnerTimes)) {
                    $closeHour = (int)explode(':', $shop->close_time)[0];
                    $dinnerTimes = array_filter($availableTimes, function($time) use ($closeHour) {
                        $hour = (int)explode(':', $time)[0];
                        return $hour >= max($closeHour - 3, 0) && $hour < $closeHour;
                    });
                }

                // ディナー時間がまだない場合は、営業時間内の最後の時間を使用
                if (empty($dinnerTimes)) {
                    $dinnerTimes = array_slice($availableTimes, -8); // 最後の2時間分（15分間隔で8個）
                }

                $dinnerTime = $this->faker->randomElement(array_values($dinnerTimes));
                $dinnerDateTime = Carbon::createFromFormat('Y-m-d H:i', $reservationDate . ' ' . $dinnerTime);

                // 支払い状態を決定
                $paymentStatus = $this->faker->randomElement(['pending', 'amount_set', 'completed', 'failed']);
                // 未決済（pending）の場合は金額を0、それ以外は金額を設定
                $totalAmount = ($paymentStatus === 'pending') ? 0 : rand(5000, 15000);

                $dinnerReservation = Reservation::create([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                    'reservation_datetime' => $dinnerDateTime,
                    'number' => rand(1, 10),
                    'total_amount' => $totalAmount,
                    'payment_status' => $paymentStatus
                ]);
                // 予約IDを使用してQRコードを生成
                $dinnerQrCodePath = 'qr_codes/' . $dinnerReservation->id . '.svg';
                $qrCodeContent = 'Reservation ID: ' . $dinnerReservation->id;
                QrCode::format('svg')->size(200)->margin(2)->generate($qrCodeContent, storage_path('app/public/' . $dinnerQrCodePath));
                $dinnerReservation->qr_code = $dinnerQrCodePath;
                $dinnerReservation->save();
            }
        }
    }
}
