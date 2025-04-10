<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //adminのrouteでreviewの一覧表示
    public function index()
    {
        $reviews = Review::with(['user', 'shop'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }
    //adminのrouteでのreview評価一覧から詳細で表示
    public function show(Review $review)
    {
        $review->load(['user', 'shop']);
        return view('admin.reviews.review', compact('review'));
    }
    //adminのrouteでreviewの削除
    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')
            ->with('success', 'レビューを削除しました。');
    }
}