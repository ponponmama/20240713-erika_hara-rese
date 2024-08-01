<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function mypage(Request $request)
    {
        // 現在認証されているユーザーを取得
        $user = Auth::user(); 
        
        $reservations = $user->reservations()->with('shop')->get(); 
        $favorites = $user->favorites;

        $hideReservation = $request->query('hide_reservation', 0);

        return view('mypage', [
            'reservations' => $reservations,
            'favorites' => $favorites,
            'hideReservation' => $hideReservation
        ]);
    }
}
