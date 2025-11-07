@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_shop_css/manage-shop.css') }}">
@endsection

@section('content')
    <div class="container">
        <p class="session-messages">
            @include('custom_components.session-messages')
        </p>
        <h2 class="title-name section-title">店舗情報</h2>
        <form action="{{ route('shop_manager.update', $shop->id) }}" method="POST" enctype="multipart/form-data"
            class="manage_form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">店舗名</label>
                <span class="data-entry shop-name-entry">{{ $shop->shop_name }}</span>
            </div>
            <div class="form-group">
                <label for="description" class="form-label">店舗紹介</label>
                <textarea id="description" name="description" class="data-entry description_text">{{ $shop->description }}</textarea>
            </div>
            <p class="form__error">
                @error('description')
                    {{ $message }}
                @enderror
            </p>
            <span class="business_hours">営業時間</span>
            <div class="form-group">
                <label for="open_time" class="form-label">
                    <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
                    オープン
                </label>
                <input type="time" id="open_time" name="open_time"
                    value="{{ \Carbon\Carbon::parse($shop->open_time)->format('H:i') }}" class="data-entry input_time">
            </div>
            <p class="form__error">
                @error('open_time')
                    {{ $message }}
                @enderror
            </p>
            <div class="form-group">
                <label for="close_time" class="form-label">
                    <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
                    クローズ
                </label>
                <input type="time" id="close_time" name="close_time"
                    value="{{ \Carbon\Carbon::parse($shop->close_time)->format('H:i') }}" class="data-entry input_time">
            </div>
            <p class="form__error">
                @error('close_time')
                    {{ $message }}
                @enderror
            </p>
            <div class="form-group">
                <label for="image" class="form-label">
                    <img src="{{ asset('images/img.png') }}" alt="" class="icon-img">
                    写真
                </label>
                <input type="file" id="image" name="image" class="input_image" accept="image/*"
                    onchange="updateFileName(this)">
                <label for="image" class="data-entry custom-file-upload">
                    <i class="fa-cloud-upload button">写真を選択</i>
                </label>
            </div>
            <p class="form__error">
                @error('image')
                    {{ $message }}
                @enderror
            </p>
            <span id="file-name" class="file-name"></span>
            <span class="preview_image"></span>
            <div class="up_date_button_container">
                <button type="submit" class="button up_date_button">更新する</button>
            </div>
        </form>
        <h3 class="confirm_text">更新された情報はこちらで確認できます</h3>
        <figure class="shop-image-wrapper">
            <img src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->shop_name }}" class="shop_image">
            <div class="shop_info">
                <p class="shop-guide">
                    @foreach ($shop->areas as $area)
                        ＃{{ $area->area_name }}
                    @endforeach
                    @foreach ($shop->genres as $genre)
                        ＃{{ $genre->genre_name }}
                    @endforeach
                </p>
                <h3 class="shop-name">{{ $shop->shop_name }}</h3>
            </div>
        </figure>
        <p class="description_title">Description</p>
        <div class="shop_info_container">
            <p class="detail-shop-guide">
                @foreach ($shop->areas as $area)
                    ＃{{ $area->area_name }}
                @endforeach
                @foreach ($shop->genres as $genre)
                    ＃{{ $genre->genre_name }}
                @endforeach
            </p>
            <p class="description">
                {{ $shop->description }}
            </p>
        </div>
        <p class="business_hours_title">営業時間の確認はこちら</p>
        <h3 class="business_hours_up">
            営業時間:{{ \Carbon\Carbon::parse($shop->open_time)->format('H:i') }}～{{ \Carbon\Carbon::parse($shop->close_time)->format('H:i') }}
        </h3>
    </div>

    <script>
        function updateFileName(input) {
            const fileName = input.files[0]?.name || '写真を選択';
            document.getElementById('file-name').textContent = fileName;

            // 画像プレビュー機能の追加
            const preview = document.querySelector('.preview_image');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}">`;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '';
            }
        }
    </script>
@endsection
