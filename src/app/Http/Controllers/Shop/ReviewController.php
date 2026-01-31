<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;
        $reviews = Review::where('shop_id', $shop->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->onEachSide(0);

        return view('shop_manager.reviews', compact('reviews'));
    }

    public function getReviewDetails($id)
    {
        try {
            $shop = auth()->user()->shop;
            $review = Review::where('shop_id', $shop->id)
                ->with('user')
                ->findOrFail($id);

            return response()->json([
                'created_at' => $review->created_at->format('Y/m/d H:i'),
                'user_name' => $review->user->user_name,
                'rating' => $review->rating,
                'comment' => $review->comment
            ]);
        } catch (\Exception $e) {
            Log::error('Review details error: ' . $e->getMessage());
            return response()->json(['error' => 'レビュー詳細の取得に失敗しました'], 500);
        }
    }
}
