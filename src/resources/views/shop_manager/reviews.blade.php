@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('shop_css/shop_review.css') }}">
    <link rel="stylesheet" href="{{ asset('shop_css/shops_modal_common.css') }}">
@endsection

@section('js')
    <script src="{{ asset('shop_css/shop_js/shop_reviews.js') }}"></script>
@endsection

@section('content')
    <div class="review_form">
        <h2 class="content-section-title">レビュー 一覧</h2>
        <table class="table-section">
            <thead>
                <tr class="review-tr">
                    <th class="review-th">投稿日時</th>
                    <th class="review-th">ユーザー名</th>
                    <th class="review-th">評価</th>
                    <th class="review-th comment-column">コメント</th>
                    <th class="review-th review-button-section">詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reviews as $review)
                    <tr class="review-tr">
                        <td class="review-td">{{ $review->created_at->format('Y/m/d H:i') }}</td>
                        <td class="review-td">{{ $review->user->user_name }}</td>
                        <td class="review-td">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <i class="fas fa-star review-star"></i>
                                @else
                                    <i class="far fa-star review-star-empty"></i>
                                @endif
                            @endfor
                        </td>
                        <td class="review-td comment-column">{{ $review->comment }}</td>
                        <td class="review-td review-button-section">
                            <button class="detail-button button" data-review-id="{{ $review->id }}">詳細</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="custom-count-pagination">
            {{ $reviews->links() }}
        </div>
    </div>

    <!-- レビュー詳細モーダル -->
    <div id="review-modal" class="review-modal modal hide">
        <div class="review-modal-content modal-content">
            <span class="close-modal-button button">&times;</span>
            <h3 class="card-title">レビュー詳細</h3>
            <div class="review-info-section">
                <div class="detail-item">
                    <h4 class="detail-title">投稿日時</h4>
                    <p class="modal-detail-section" id="modal-review-created-at"></p>
                </div>
                <div class="detail-item">
                    <h4 class="detail-title">ユーザー名</h4>
                    <p class="modal-detail-section" id="modal-review-user-name"></p>
                </div>
                <div class="detail-item">
                    <h4 class="detail-title">評価</h4>
                    <p class="modal-detail-section" id="modal-review-rating"></p>
                </div>
                <div class="detail-item shop-comment-item">
                    <h4 class="detail-title comment-title">コメント</h4>
                    <p class="modal-detail-section comment-item" id="modal-review-comment"></p>
                </div>
            </div>
        </div>
    </div>
@endsection
