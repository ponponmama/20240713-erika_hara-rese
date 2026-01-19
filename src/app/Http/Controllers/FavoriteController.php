<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    //お気に入りの追加
    public function favorite(Shop $shop)
    {
        /** @var User $user */
        $user = Auth::user();

        // 一般ユーザー（role 3）のみお気に入り登録可能
        if ($user->role !== 3) {
            return back()->with('favorite_error', 'お気に入り登録は一般ユーザーのみ利用可能です。');
        }

        Log::info('Adding to favorites', ['user_id' => $user->id, 'shop_id' => $shop->id]);

        // 既にお気に入りに追加されているか確認
        $alreadyExists = $user->favorites()->where('shop_id', $shop->id)->exists();

        // お気に入りに追加
        $user->favorites()->syncWithoutDetaching([$shop->id]);

        // 既に存在する場合はupdated_atのみ更新、そうでなければcreated_atとupdated_atを更新
        if ($alreadyExists) {
            $user->favorites()->updateExistingPivot($shop->id, ['updated_at' => now()]);
        } else {
            $user->favorites()->updateExistingPivot($shop->id, ['created_at' => now(), 'updated_at' => now()]);
        }

        Log::info('Authenticated user:', ['user' => $user]);
        return back()->with('favorite_success', 'お気に入りに追加しました！');
    }

    //お気に入りの解除
    public function unfavorite(Shop $shop)
    {
        /** @var User $user */
        $user = Auth::user();

        // 一般ユーザー（role 3）のみお気に入り解除可能
        if ($user->role !== 3) {
            return back()->with('favorite_error', 'お気に入り解除は一般ユーザーのみ利用可能です。');
        }

        $user->favorites()->detach($shop->id);
        Log::info('Removing from favorites', ['user_id' => $user->id, 'shop_id' => $shop->id]);
        return back()->with('favorite_success', 'お気に入りを解除しました！');
    }
}
