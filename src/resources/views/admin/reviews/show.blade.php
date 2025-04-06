@extends('admin.app_admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_shop_css/admin.css') }}">
@endsection

@section('content')
<div class="container review-management">
    <h2 class="mb-4">レビュー詳細</h2>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>店舗情報</h5>
                    <p><strong>店舗名：</strong>{{ $review->shop->shop_name }}</p>
                </div>
                <div class="col-md-6">
                    <h5>ユーザー情報</h5>
                    <p><strong>ユーザー名：</strong>{{ $review->user->user_name }}</p>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h5>評価</h5>
                    <div class="mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="fas fa-star text-warning fa-2x"></i>
                            @else
                                <i class="far fa-star text-warning fa-2x"></i>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h5>コメント</h5>
                    <p class="border p-3 bg-light">{{ $review->comment }}</p>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h5>投稿日時</h5>
                    <p>{{ $review->created_at->format('Y/m/d H:i') }}</p>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> 一覧に戻る
                </a>
                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('このレビューを削除してもよろしいですか？')">
                        <i class="fas fa-trash"></i> 削除
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
