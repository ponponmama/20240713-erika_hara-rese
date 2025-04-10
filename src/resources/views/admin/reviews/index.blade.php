@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_shop_css/admin.css') }}">
@endsection

@section('content')
<div class="container">
    @include('custom_components.header', [
        'title' => 'レビュー管理'
    ])
    <div class="admin_container">
        @include('custom_components.session-messages')
        <div class="management_form review_form">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>投稿日時</th>
                        <th>店舗名</th>
                        <th>ユーザー名</th>
                        <th>評価</th>
                        <th>コメント</th>
                        <th>詳細</th>
                        <th>削除</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td>{{ $review->created_at->format('Y/m/d H:i') }}</td>
                            <td>{{ $review->shop->shop_name }}</td>
                            <td>{{ $review->user->user_name }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star review-star"></i>
                                    @else
                                        <i class="far fa-star review-star-empty"></i>
                                    @endif
                                @endfor
                            </td>
                            <td>{{ Str::limit($review->comment, 50) }}</td>
                            <td>
                                <button onclick="openReviewModal({{ $review->id }})" class="review_button review-detail-button">詳細</button>
                            </td>
                            <td>
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="review_button review-delete-button" onclick="return confirm('このレビューを削除してもよろしいですか？')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="custom-count-pagination">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>

<!-- レビュー詳細モーダル -->
<div id="reviewModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>レビュー詳細</h2>
        <div id="reviewDetails">
            <!-- ここにレビュー詳細が動的に表示されます -->
        </div>
    </div>
</div>

<script>
    // モーダルを開く関数
    function openReviewModal(reviewId) {
        console.log('openReviewModal called with ID:', reviewId);
        const modal = document.getElementById('reviewModal');
        const detailsContainer = document.getElementById('reviewDetails');

        console.log('Modal element:', modal);
        console.log('Details container:', detailsContainer);

        // レビュー詳細を取得して表示
        fetch(`/admin/reviews/${reviewId}/details`)
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= data.rating) {
                        starsHtml += '<i class="fas fa-star review-star"></i>';
                    } else {
                        starsHtml += '<i class="far fa-star review-star-empty"></i>';
                    }
                }

                detailsContainer.innerHTML = `
                    <div class="detail-row">
                        <span class="detail-label">投稿日時:</span>
                        <span>${data.created_at}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">店舗名:</span>
                        <span>${data.shop_name}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">ユーザー名:</span>
                        <span>${data.user_name}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">評価:</span>
                        <span>${starsHtml}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">コメント:</span>
                        <span>${data.comment}</span>
                    </div>
                `;
                console.log('Adding modal-show class');
                modal.classList.add('modal-show');
                console.log('Modal classes after adding:', modal.classList);
            })
            .catch(error => {
                console.error('Error fetching review details:', error);
            });
    }

    // モーダルを閉じる
    document.querySelector('.close').onclick = function() {
        console.log('Close button clicked');
        document.getElementById('reviewModal').classList.remove('modal-show');
    }

    // モーダルの外をクリックしても閉じる
    window.onclick = function(event) {
        const modal = document.getElementById('reviewModal');
        if (event.target == modal) {
            console.log('Clicked outside modal');
            modal.classList.remove('modal-show');
        }
    }

    // ページ読み込み時にモーダル要素を確認
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded');
        const modal = document.getElementById('reviewModal');
        console.log('Modal element on load:', modal);
        console.log('Modal classes on load:', modal ? modal.classList : 'Modal not found');
    });
</script>
@endsection
