<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function add(Request $request, $id)
        {
           $favorite = new Favorite();
           $favorite->shop_id = $id;
           $favorite->user_id = auth()->user()->id; // 認証されたユーザーのID
           $favorite->save();

           return redirect()->back()->with('success', 'お気に入りに追加しました！');
        } 
}
