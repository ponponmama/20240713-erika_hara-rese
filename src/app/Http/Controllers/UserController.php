<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function mypage()
    {
        // 現在認証されているユーザーを取得
        $user = Auth::user(); 
        // userモデル＆shopモデルに定義されたリレーションを使用して予約情報を取得
        $reservations = $user->reservations()->with('shop')->get(); 

        return view('mypage', ['reservations' => $reservations]);
    }
}
