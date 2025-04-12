@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('admin_shop_css/shops_list.css') }}">
@endsection

@section('content')
<div class="container shops_list_container">
    @include('custom_components.header', [
    'title' => 'admin登録店舗一覧',
    'userName' => Auth::user()->user_name,
    'message' => 'お疲れ様です！',
    'showMessage' => true
    ])

    @include('custom_components.session-messages')

    <div class="management_form shop_list_form">
        <h2 class="admin-heading shop_list">登録店舗</h2>

        <table class="admin-table shop_list_table">
            <thead>
                <tr>
                    <th>店舗名</th>
                    <th>エリア</th>
                    <th>ジャンル</th>
                    <th>営業時間</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @if(count($shops) > 0)
                    @foreach($shops as $shop)
                        <tr>
                            <td>{{ $shop->shop_name }}</td>
                            <td>
                                @foreach($shop->areas as $area)
                                    {{ $area->area_name }}
                                    @if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($shop->genres as $genre)
                                    {{ $genre->genre_name }}
                                    @if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>{{ $shop->open_time }} - {{ $shop->close_time }}</td>
                            <td>
                                <button class="admin-button detail-button" data-shop-id="{{ $shop->id }}">詳細</button>
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
    </div>
</div>

<!-- 店舗詳細モーダル -->
<div id="shop-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-shop-name"></h2>
        <div class="shop-details">
            <div class="detail-item">
                <h3>店舗詳細</h3>
                <p id="modal-shop-description"></p>
            </div>

            <div class="detail-item">
                <h3>エリア</h3>
                <p id="modal-shop-area"></p>
            </div>

            <div class="detail-item">
                <h3>ジャンル</h3>
                <p id="modal-shop-genre"></p>
            </div>

            <div class="detail-item">
                <h3>営業時間</h3>
                <p id="modal-shop-hours"></p>
            </div>

            <div class="detail-item" id="modal-shop-image-container" style="display: none;">
                <h3>店舗画像</h3>
                <img id="modal-shop-image" src="" alt="" class="shop-image">
            </div>

            <div class="detail-item">
                <form id="delete-form" action="" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-button delete-button" onclick="return confirm('本当にこの店舗を削除しますか？')">削除</button>
                </form>
            </div>
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
                    document.getElementById('modal-shop-name').textContent = data.shop_name;
                    document.getElementById('modal-shop-description').textContent = data.description;

                    // エリアとジャンルは配列なので結合
                    const areaNames = data.areas.map(area => area.area_name).join(', ');
                    const genreNames = data.genres.map(genre => genre.genre_name).join(', ');

                    document.getElementById('modal-shop-area').textContent = areaNames;
                    document.getElementById('modal-shop-genre').textContent = genreNames;
                    document.getElementById('modal-shop-hours').textContent = `${data.open_time} - ${data.close_time}`;

                    // 画像がある場合のみ表示
                    if (data.image) {
                        document.getElementById('modal-shop-image').src = `/storage/${data.image}`;
                        document.getElementById('modal-shop-image').alt = data.shop_name;
                        document.getElementById('modal-shop-image-container').style.display = 'block';
                    } else {
                        document.getElementById('modal-shop-image-container').style.display = 'none';
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
