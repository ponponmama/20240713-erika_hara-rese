@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_css/admin_shops_list.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_css/admin_modal_common.css') }}">
@endsection

@section('js')
    <script src="{{ asset('admin_js/admin_shops_list.js') }}"></script>
@endsection

@section('content')
    <div class="container shops_list_container">
        @include('custom_components.header', [
            'title' => '登録店舗一覧',
            'userName' => Auth::user()->user_name,
            'message' => 'お疲れ様です！',
            'showMessage' => true,
        ])
        <p class="session-messages">
            @include('custom_components.session-messages')
        </p>
        <div class="management_form shop_list_form">
            <table class="table-section shop_list_table">
                <thead class="admin-thead">
                    <tr class="admin-tr">
                        <th class="admin-th">店舗名</th>
                        <th class="admin-th">エリア</th>
                        <th class="admin-th">ジャンル</th>
                        <th class="admin-th">営業時間</th>
                        <th class="admin-th admin-info">詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($shops) > 0)
                        @foreach ($shops as $shop)
                            <tr class="admin-tr">
                                <td class="admin-td">{{ $shop->shop_name }}</td>
                                <td class="admin-td">
                                    @foreach ($shop->areas as $area)
                                        {{ $area->area_name }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td class="admin-td">
                                    @foreach ($shop->genres as $genre)
                                        {{ $genre->genre_name }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td class="admin-td">{{ $shop->open_time }} - {{ $shop->close_time }}</td>
                                <td class="admin-td admin-button-section">
                                    <button class="admin-button detail-button"
                                        data-shop-id="{{ $shop->id }}">詳細</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">店舗が登録されていません</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="custom-count-pagination">
                {{ $shops->links() }}
            </div>
        </div>
    </div>

    <!-- 店舗詳細モーダル -->
    <div id="shop-modal" class="details-modal modal">
        <div class="details-modal-content modal-content">
            <span class="close-modal-button">&times;</span>
            <h3 class="card-title">登録店舗情報</h3>
            <div id="modal-shop-image-container" class="detail-shop-cards">
                <img id="modal-shop-image" src="" alt="店舗画像" class="shop-image">
                <div class="details-shop-info">
                    <div class="detail-item">
                        <h4 class="detail-title">店舗名</h4>
                        <p class="modal-detail-section" id="modal-shop-name"></p>
                    </div>
                    <div class="detail-item shop-description-item">
                        <h4 class="detail-title">店舗案内</h4>
                        <p class="modal-detail-section shop-description-item" id="modal-shop-description"></p>
                    </div>
                    <div class="detail-item">
                        <h4 class="detail-title shop-tags-title">エリア</h4>
                        <p class="modal-detail-section" id="modal-shop-area"></p>
                    </div>
                    <div class="detail-item">
                        <h4 class="detail-title shop-tags-title">ジャンル</h4>
                        <p class="modal-detail-section" id="modal-shop-genre"></p>
                    </div>
                    <div class="detail-item">
                        <h4 class="detail-title shop-hours-title">営業時間</h4>
                        <p class="modal-detail-section" id="modal-shop-hours"></p>
                    </div>
                </div>
            </div>
            <div class="detail-item delete-item">
                <form id="delete-form" action="" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-button delete-button" onclick="return confirm('本当にこの店舗を削除しますか？')">削除</button>
                </form>
            </div>
        </div>
    </div>
@endsection
