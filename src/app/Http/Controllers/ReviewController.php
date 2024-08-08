<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|integer',
            'rating' => 'required|integer|min:max:5',
            'comment' => 'required|string'
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'shop_id' => $request->shop_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->back()->with('success', 'レビューが投稿されました。');
    }
}
