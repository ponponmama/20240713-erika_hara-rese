@extends('admin.app_admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_shop_css/admin.css') }}">
@endsection

@section('content')
<div class="admin_container">
    <h1 class="form-title">レビュー管理</h1>
    <p class="user__name">お疲れ様です！　{{ Auth::user()->user_name }}さん</p>
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="management_form review_form">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>投稿日時</th>
                    <th>店舗名</th>
                    <th>ユーザー名</th>
                    <th>評価</th>
                    <th>コメント</th>
                    <th>操作</th>
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
                            <div class="review-button-container">
                                <a href="{{ route('admin.reviews.show', $review) }}" class="review_button review-detail-button">詳細</a>
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="review_button review-delete-button" onclick="return confirm('このレビューを削除してもよろしいですか？')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
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
@endsection
