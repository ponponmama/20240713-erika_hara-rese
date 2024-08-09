<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor; // Visitorモデルを使用する場合
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VisitorController extends Controller
{
    public function showQrCode($id)
    {
        $visitor = Visitor::findOrFail($id); // IDに基づいて来店者情報を取得
        $qrCode = QrCode::size(300)->generate($visitor->unique_code); // QRコードを生成

        return view('visitor.qrcode', compact('qrCode')); // ビューにQRコードを渡す
    }
}