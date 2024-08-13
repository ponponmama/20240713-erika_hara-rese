<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Libern\QRCodeReader\QRCodeReader; 


class QRController extends Controller
{
    public function decodeQRFromId($id)
    {   
        $reservation = Reservation::findOrFail($id);
        $qrCodePath = public_path($reservation->qr_code);
        \Log::info("QR Code Path: " . $qrCodePath);

        try {
            $qrcode = new QRCodeReader(); // 初期化方法を変更
            $text = $qrcode->decode($qrCodePath); // メソッドを変更
        } catch (\Exception $e) {
            \Log::error("QR Decode error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return redirect()->back()->with('result', $text); // 結果をセッションに保存して元のページに戻る
    }
}