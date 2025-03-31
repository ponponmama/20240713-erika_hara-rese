<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function view(User $user, Review $review)
    {
        // 管理者は全てのレビューを見れる
        if ($user->role === 1) {
            return true;
        }

        // ショップオーナーは自分の店舗のレビューのみ見れる
        if ($user->role === 2) {
            return $user->admin->shop_id === $review->shop_id;
        }

        return false;
    }

    public function delete(User $user, Review $review)
    {
        // 管理者のみ削除可能
        return $user->role === 1;
    }
}