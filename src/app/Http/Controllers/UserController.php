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
        
        $reservations = $user->reservations()->with('shop')->get(); 
        $favorites = $user->favorites;

        return view('mypage', ['reservations' => $reservations,
        'favorites' => $favorites]);
    }
}
