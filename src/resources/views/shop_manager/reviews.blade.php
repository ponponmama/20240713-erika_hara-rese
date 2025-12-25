@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('shop_css/shop_review.css') }}">
@endsection

@section('content')
    <p class="review-title">レビュー 一覧</p>
    <div class="review_form">
        <table class="table-section reviews-table">
            <thead>
                <tr class="review-th">
                    <th class="review-th">投稿日時</th>
                    <th class="review-th">ユーザー名</th>
                    <th class="review-th">評価</th>
                    <th class="review-th">コメント</th>
                    </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                    <tr class="review-tr">
                        <td class="review-td">{{ $review->created_at->format('Y/m/d H:i') }}</td>
                        <td class="review-td">{{ $review->user->user_name }}</td>
                        <td class="review-td">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star review-star"></i>
                                @else
                                    <i class="far fa-star review-star-empty"></i>
                                @endif
                            @endfor
                        </td>
                        <td class="comment-column">{{ Str::limit($review->comment, 50) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="custom-count-pagination">
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
