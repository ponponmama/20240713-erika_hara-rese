<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->admin->shop;
        $reviews = Review::where('shop_id', $shop->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('shop.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $this->authorize('view', $review);
        $review->load('user');
        return view('shop.reviews.show', compact('review'));
    }
}
