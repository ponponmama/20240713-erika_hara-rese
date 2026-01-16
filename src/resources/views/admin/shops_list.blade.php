@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_css/admin_shops_list.css') }}">
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
                                <td class="admin-button-section">
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
    <div id="shop-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modal-shop-image-container" class="shop-image-container" style="display: none;">
                <h3 class="shop-image-title">登録店舗情報</h3>
                <img id="modal-shop-image" src="" alt="" class="shop-image">
            </div>
            <div class="shop-details">
                <div class="detail-item">
                    <h4 class="shop-title">店舗名</h4>
                    <p class="modal-title-section" id="modal-shop-name"></p>
                </div>
                <div class="detail-item shop-description-item">
                    <h4 class="shop-title">店舗詳細</h4>
                    <p class="modal-title-section" id="modal-shop-description"></p>
                </div>
                <div class="detail-item">
                    <h4 class="shop-title shop-image-title">エリア</h4>
                    <p class="modal-title-section" id="modal-shop-area"></p>
                </div>
                <div class="detail-item">
                    <h4 class="shop-title shop-image-title">ジャンル</h4>
                    <p class="modal-title-section" id="modal-shop-genre"></p>
                </div>
                <div class="detail-item">
                    <h4 class="shop-title shop-image-title">営業時間</h4>
                    <p class="modal-title-section" id="modal-shop-hours"></p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('shop-modal');
            const span = document.getElementsByClassName('close')[0];
            const detailButtons = document.querySelectorAll('.detail-button');
            const deleteForm = document.getElementById('delete-form');

            // 詳細ボタンクリック時
            detailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const shopId = this.getAttribute('data-shop-id');

                    // 店舗詳細を取得
                    fetch(`/admin/shops/${shopId}/details`)
                        .then(response => response.json())
                        .then(data => {
                            // モーダルにデータを表示
                            document.getElementById('modal-shop-name').textContent = data
                                .shop_name;
                            document.getElementById('modal-shop-description').textContent = data
                                .description;

                            // エリアとジャンルは配列なので結合
                            const areaNames = data.areas.map(area => area.area_name).join(', ');
                            const genreNames = data.genres.map(genre => genre.genre_name).join(
                                ', ');

                            document.getElementById('modal-shop-area').textContent = areaNames;
                            document.getElementById('modal-shop-genre').textContent =
                                genreNames;
                            document.getElementById('modal-shop-hours').textContent =
                                `${data.open_time} - ${data.close_time}`;

                            // 画像がある場合のみ表示
                            if (data.image) {
                                document.getElementById('modal-shop-image').src =
                                    `/storage/${data.image}`;
                                document.getElementById('modal-shop-image').alt = data
                                    .shop_name;
                                document.getElementById('modal-shop-image-container').style
                                    .display = 'block';
                            } else {
                                document.getElementById('modal-shop-image-container').style
                                    .display = 'none';
                            }

                            // 削除フォームのアクションを設定
                            deleteForm.action = `/admin/shops/${shopId}`;
                            deleteForm.setAttribute('data-shop-id', shopId);

                            // モーダルを表示
                            modal.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('店舗情報の取得に失敗しました');
                        });
                });
            });

            // モーダルを閉じる
            span.onclick = function() {
                modal.style.display = 'none';
            }

            // モーダル外をクリックして閉じる
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        });
    </script>
@endsection
