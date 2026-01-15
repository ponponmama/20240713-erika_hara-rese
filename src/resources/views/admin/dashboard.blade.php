@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_css/admin_modal.css') }}">
    <link rel="stylesheet" href="{{ asset('users_css/index.css') }}">
@endsection

@section('content')
    <div class="container admin_container">
        @include('custom_components.header', [
            'userName' => Auth::user()->user_name,
            'message' => 'お疲れ様です！',
            'showMessage' => true,
        ])
        <div class="management_form shop_manager_form">
            @include('custom_components.header', [
                'title' => '店舗代表者登録',
                'useFormTitle' => true,
                'showMessage' => false,
                'showUserName' => false,
                'headingLevel' => 3,
            ])
            <p class="session-messages">
                @include('custom_components.session-messages', [
                    'showGeneral' => false,
                    'showShopManager' => true,
                    'showShop' => false,
                ])
            </p>
            <form action="{{ route('admin.create.shop_manager') }}" method="POST" class="admin-form create-form">
                @csrf
                <div class="input-group">
                    <img src="{{ asset('images/shop.png') }}" alt="" class="icon-img">
                    <div class="select-wrapper">
                        <select id="shop_id" name="shop_id" class="data-entry select_shop_id">
                            <option value="">店舗を選択してください</option>
                            @foreach ($shops ?? [] as $shop)
                                <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->shop_name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="custom-select-icon"></span>
                    </div>
                </div>
                <p class="form__error">
                    @error('shop_id')
                        {{ $message }}
                    @enderror
                </p>
                <div class="input-group">
                    <img src="{{ asset('images/human.png') }}" alt="" class="icon-img">
                    <input type="text" id="user_name" name="user_name" placeholder="Username"
                        value="{{ old('user_name') }}" class="data-entry">
                </div>
                <p class="form__error">
                    @error('user_name')
                        {{ $message }}
                    @enderror
                </p>
                <div class="input-group">
                    <img src="{{ asset('images/mail.png') }}" alt="" class="icon-img">
                    <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}"
                        class="data-entry">
                </div>
                <p class="form__error">
                    @error('email')
                        {{ $message }}
                    @enderror
                </p>
                <div class="input-group">
                    <img src="{{ asset('images/key.png') }}" alt="" class="icon-img">
                    <input type="password" id="password" name="password" placeholder="Password"
                        value="{{ old('password') }}" class="data-entry">
                </div>
                <p class="form__error">
                    @error('password')
                        {{ $message }}
                    @enderror
                </p>
                <button class="button register-button" type="submit">店舗代表者登録</button>
            </form>
        </div>
        <div class="management_form shop_registration_form">
            @include('custom_components.header', [
                'title' => '新規店舗登録',
                'useFormTitle' => true,
                'showMessage' => false,
                'showUserName' => false,
                'headingLevel' => 3,
            ])
            @include('custom_components.session-messages', [
                'showGeneral' => false,
                'showShopManager' => false,
                'showShop' => true,
            ])
            <form action="{{ route('admin.create.shop') }}" method="POST" class="admin-form create-shop-form"
                enctype="multipart/form-data">
                @csrf
                <div class="input-group">
                    <img src="{{ asset('images/shop.png') }}" alt="" class="icon-img">
                    <input type="text" name="shop_name" placeholder="Shop Name" value="{{ old('shop_name') }}"
                        class="data-entry">
                </div>
                <p class="form__error">
                    @error('shop_name')
                        {{ $message }}
                    @enderror
                </p>
                <div class="input-group">
                    <img src="{{ asset('images/description.png') }}" alt="" class="icon-img">
                    <textarea name="description" placeholder="Description" class="data-entry description_text">{{ old('description') }}</textarea>
                </div>
                <p class="form__error">
                    @error('description')
                        {{ $message }}
                    @enderror
                </p>
                <div class="input-group">
                    <img src="{{ asset('images/genre.png') }}" alt="" class="icon-img">
                    <input type="text" name="genre_name" placeholder="Genre" value="{{ old('genre_name') }}"
                        class="data-entry">
                </div>
                <p class="form__error">
                    @error('genre_name')
                        {{ $message }}
                    @enderror
                </p>
                <div class="input-group">
                    <img src="{{ asset('images/area.png') }}" alt="" class="icon-img">
                    <input type="text" name="area_name" placeholder="Area" value="{{ old('area_name') }}"
                        class="data-entry">
                </div>
                <p class="form__error">
                    @error('area_name')
                        {{ $message }}
                    @enderror
                </p>
                <div class="input-group">
                    <img src="{{ asset('images/img.png') }}" alt="" class="icon-img">
                    <input type="file" id="image" name="image" class="admin-input input_image">
                    <label for="image" class="custom-file-upload data-entry">
                        <i class="fa-cloud-upload">
                            <span id="file-name" class="file-name-display"></span>
                        </i>写真を選択
                    </label>
                </div>
                <p class="form__error">
                    @error('image')
                        {{ $message }}
                    @enderror
                </p>
                <label for="open_time" class="admin-time-label">
                    オープン
                </label>
                <div class="input-group-time">
                    <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
                    <input type="time" id="open_time" name="open_time" class="data-entry time-input">
                </div>
                <p class="form__error">
                    @error('open_time')
                        {{ $message }}
                    @enderror
                </p>
                <label for="close_time" class="admin-time-label">
                    クローズ
                </label>
                <div class="input-group-time">
                    <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
                    <input type="time" id="close_time" name="close_time" class="data-entry time-input admin-input">
                </div>
                <p class="form__error">
                    @error('close_time')
                        {{ $message }}
                    @enderror
                </p>
                <button class="button new-register-button" type="submit">新店舗登録</button>
            </form>
        </div>
    </div>

    <!-- 店舗登録確認モーダル -->
    @if (session('shop_success') && session('new_shop_id'))
        <div id="shop-registration-modal" class="registration-modal" style="display: block;">
            <div class="registration-modal-content">
                <span class="close-registration-modal">&times;</span>
                <h2>店舗登録完了</h2>
                <div class="registration-modal-body">
                    <div class="registered-shop-detail">
                        <h3>登録した店舗</h3>
                        @php
                            $newShop = \App\Models\Shop::with(['areas', 'genres'])->find(session('new_shop_id'));
                        @endphp
                        @if ($newShop)
                            <div class="registered-shop-card">
                                @if ($newShop->image)
                                    <img src="{{ asset('storage/' . $newShop->image) }}" alt="{{ $newShop->shop_name }}"
                                        class="registered-shop-image">
                                @else
                                    <div class="registered-shop-image-placeholder">画像なし</div>
                                @endif
                                <div class="registered-shop-info">
                                    <h4>{{ $newShop->shop_name }}</h4>
                                    <p class="shop-tags">
                                        @foreach ($newShop->areas as $area)
                                            ＃{{ $area->area_name }}
                                        @endforeach
                                        @foreach ($newShop->genres as $genre)
                                            ＃{{ $genre->genre_name }}
                                        @endforeach
                                    </p>
                                    <p class="shop-hours">{{ $newShop->open_time }} - {{ $newShop->close_time }}</p>
                                    <p class="shop-description">{{ Str::limit($newShop->description, 100) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="shops-list-mini">
                        <h3>店舗一覧</h3>
                        <div class="shops-list-scroll">
                            <div class="shop_table modal-shop-table">
                                @foreach ($shops as $shop)
                                    <div class="shop_card modal-shop-card">
                                        @if ($shop->image)
                                            <img src="{{ asset('storage/' . $shop->image) }}"
                                                alt="{{ $shop->shop_name }}">
                                        @else
                                            <div class="shop-image-placeholder">画像なし</div>
                                        @endif
                                        <div class="shop_info">
                                            <h3 class="shop-name">{{ $shop->shop_name }}</h3>
                                            <p class="shop-guide">
                                                @foreach ($shop->areas as $area)
                                                    ＃{{ $area->area_name }}
                                                @endforeach
                                                @foreach ($shop->genres as $genre)
                                                    ＃{{ $genre->genre_name }}
                                                @endforeach
                                            </p>
                                            @include('custom_components.shop-buttons', [
                                                'shop' => $shop,
                                                'showFavoriteForm' => false,
                                            ])
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <button class="button close-modal-button" onclick="closeRegistrationModal()">閉じる</button>
            </div>
        </div>
    @endif

    <script>
        document.getElementById('image').addEventListener('change', function() {
            var fileName = this.files[0].name;
            var fileLabel = document.getElementById('file-name');
            fileLabel.textContent = fileName;
        });

        // モーダルを閉じる
        function closeRegistrationModal() {
            document.getElementById('shop-registration-modal').style.display = 'none';
        }

        // ×ボタンで閉じる
        document.addEventListener('DOMContentLoaded', function() {
            const closeBtn = document.querySelector('.close-registration-modal');
            if (closeBtn) {
                closeBtn.onclick = function() {
                    closeRegistrationModal();
                }
            }

            // モーダル外をクリックして閉じる
            window.onclick = function(event) {
                const modal = document.getElementById('shop-registration-modal');
                if (event.target == modal) {
                    closeRegistrationModal();
                }
            }
        });
    </script>
@endsection
