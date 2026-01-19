<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    //来店後のレビューの投稿をデータベースに保存
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // 一般ユーザー（role 3）のみレビュー投稿可能
        if ($user->role !== 3) {
            return back()->with('review_error', 'レビュー投稿は一般ユーザーのみ利用可能です。');
        }

        // リクエストデータのバリデーション
        $request->validate([
            'shop_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string'
        ]);

        // レビューデータの作成と保存
        Review::create([
            'user_id' => auth()->id(),
            'shop_id' => $request->shop_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        // 前のページにリダイレクトし、成功メッセージをフラッシュセッションに追加
        return redirect()->back()->with('review_success', 'レビューが投稿されました。');
    }
}
