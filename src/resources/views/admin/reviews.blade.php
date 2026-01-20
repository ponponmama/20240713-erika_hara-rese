@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_css/admin_reviews.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_css/admin_modal_common.css') }}">
@endsection

@section('js')
    <script src="{{ asset('admin_js/admin_reviews.js') }}"></script>
@endsection

@section('content')
    @include('custom_components.header', [
        'title' => 'レビュー　一覧',
        'userName' => Auth::user()->user_name,
        'message' => 'お疲れ様です！',
        'showMessage' => true,
    ])
    <p class="session-messages">
        @include('custom_components.session-messages')
    </p>
    <div class="review_form">
        <table class="table-section">
            <thead>
                <tr class="review-tr">
                    <th class="review-th">投稿日時</th>
                    <th class="review-th">店舗名</th>
                    <th class="review-th">ユーザー名</th>
                    <th class="review-th">評価</th>
                    <th class="review-th">コメント</th>
                    <th class="review-th review-info">詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reviews as $review)
                    <tr class="review-tr">
                        <td class="review-td">{{ $review->created_at->format('Y/m/d H:i') }}</td>
                        <td class="review-td">{{ $review->shop->shop_name }}</td>
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
                        <td class="comment-column" title="{{ $review->comment }}">{{ Str::limit($review->comment, 50) }}</td>
                        <td class="review-button-section">
                            <button class="review-button detail-button" data-review-id="{{ $review->id }}">詳細</button>
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
    <div id="review-modal" class="review-modal modal">
        <div class="review-modal-content modal-content">
            <span class="close-modal-button">&times;</span>
            <h3 class="card-title">レビュー詳細</h3>
            <div class="review-info-section">
                <div class="detail-item">
                    <h4 class="detail-title">投稿日時</h4>
                    <p class="modal-detail-section" id="modal-review-created-at"></p>
                </div>
                <div class="detail-item">
                    <h4 class="detail-title">店舗名</h4>
                    <p class="modal-detail-section" id="modal-review-shop-name"></p>
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
            <div class="detail-item">
                <form id="delete-form" action="" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-button delete-button"
                        onclick="return confirm('このレビューを削除してもよろしいですか？')">削除</button>
                </form>
            </div>
        </div>
    </div>
@endsection
