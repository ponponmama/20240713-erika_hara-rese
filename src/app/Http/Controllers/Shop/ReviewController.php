<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;
        $reviews = Review::where('shop_id', $shop->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('shop_manager.index', compact('reviews'));
    }
}
