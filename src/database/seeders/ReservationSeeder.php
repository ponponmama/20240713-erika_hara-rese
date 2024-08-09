<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Shop;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservationSeeder extends Seeder
{
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
            // 各ショップに対して5件の予約を作成
            for ($i = 0; $i < 3; $i++) {

                $user = User::where('role', 3)->inRandomOrder()->first();
                $lunchDateTime = now()->addDays(rand(1, 30))->hour(12)->minute(0)->second(0);
                $lunchQrCodePath = 'qr_codes/lunch_' . $user->id . '_' . $shop->id . '_' . $lunchDateTime->format('YmdHis') . '.png';
                QrCode::format('png')->size(100)->generate('Reservation ID: ' . $user->id, public_path($lunchQrCodePath));

                Reservation::create([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                    'reservation_datetime' => $lunchDateTime,
                    'number' => rand(1, 10),
                    'qr_code' => $lunchQrCodePath
                ]);

                $dinnerDateTime = now()->addDays(rand(1, 30))->hour(18)->minute(0)->second(0);
                $dinnerQrCodePath = 'qr_codes/dinner_' . $user->id . '_' . $shop->id . '_' . $dinnerDateTime->format('YmdHis') . '.png';
                QrCode::format('png')->size(100)->generate('Reservation ID: ' . $user->id, public_path($dinnerQrCodePath));

                Reservation::create([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                    'reservation_datetime' => $dinnerDateTime,
                    'number' => rand(1, 10),
                    'qr_code' => $dinnerQrCodePath
                ]);

            }
        }
    }
}
