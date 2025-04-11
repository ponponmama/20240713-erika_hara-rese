@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_shop_css/admin.css') }}">
@endsection

@section('content')
<div class="container admin_container">
    @include('custom_components.header', [
        'title' => 'Shop Manager Registration',
        'userName' => Auth::user()->user_name,
        'message' => 'お疲れ様です！',
        'showMessage' => true
    ])
    <div class="management_form shop_manager_form">
        <h2 class="admin-heading manage_admin">店舗代表者登録</h2>
        <form action="{{ route('admin.create.shop_manager') }}" method="POST" class="admin-form create-form">
            @csrf
            <div class="input-group">
                <img src="{{ asset('images/shop.png') }}" alt="" class="icon-img">
                <div class="select-wrapper">
                    <select id="shop_id" name="shop_id" class="select_shop_id admin-border">
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
                <input type="text" id="user_name" name="user_name" placeholder="Username" value="{{ old('user_name') }}" class="admin-input input_user_name">
            </div>
            <p class="form__error">
                @error('user_name')
                    {{ $message }}
                @enderror
            </p>
            <div class="input-group">
                <img src="{{ asset('images/mail.png') }}" alt="" class="icon-img">
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" class="admin-input input_email">
            </div>
            <p class="form__error">
                @error('email')
                    {{ $message }}
                @enderror
            </p>
            <div class="input-group">
                <img src="{{ asset('images/key.png') }}" alt="" class="icon-img">
                <input type="password" id="password" name="password" placeholder="Password" value="{{ old('password') }}" class="admin-input input_password">
            </div>
            <p class="form__error">
                @error('password')
                    {{ $message }}
                @enderror
            </p>
            <button class="admin-button register-button" type="submit">店舗代表者登録</button>
        </form>
    </div>
    <div class="management_form shop_registration_form">
        @if (session('success'))
            <div class="admin-alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="admin-alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <h2 class="admin-heading shop_manage">新規店舗登録</h2>
        <form action="{{ route('admin.create.shop') }}" method="POST" class="admin-form create-shop-form" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
                <img src="{{ asset('images/shop.png') }}" alt="" class="icon-img">
                <input type="text" name="shop_name" placeholder="Shop Name" value="{{ old('shop_name') }}" class="admin-input input_shop_name">
            </div>
            <p class="form__error">
                @error('shop_name')
                    {{ $message }}
                @enderror
            </p>
            <div class="input-group">
                <img src="{{ asset('images/description.png') }}" alt="" class="icon-img">
                <textarea name="description" placeholder="Description" class="description_text admin-border">{{ old('description') }}</textarea>
            </div>
            <p class="form__error">
                @error('description')
                    {{ $message }}
                @enderror
            </p>
            <div class="input-group">
                <img src="{{ asset('images/genre.png') }}" alt="" class="icon-img">
                <input type="text" name="genre_name" placeholder="Genre" value="{{ old('genre_name') }}" class="admin-input input_genre">
            </div>
            <p class="form__error">
                @error('genre_name')
                    {{ $message }}
                @enderror
            </p>
            <div class="input-group">
                <img src="{{ asset('images/area.png') }}" alt="" class="icon-img">
                <input type="text" name="area_name" placeholder="Area" value="{{ old('area_name') }}" class="admin-input input_area">
            </div>
            <p class="form__error">
                @error('area_name')
                    {{ $message }}
                @enderror
            </p>
            <div class="input-group">
                <img src="{{ asset('images/img.png') }}" alt="" class="icon-img">
                <input type="file" id="image" name="image" class="input_image" >
                <label for="image" class="custom-file-upload admin-border">
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
            <div class="input-group-time">
                <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
                <label for="open_time" class="admin-time-label label_open_time">オープン</label>
                <input type="time" id="open_time" name="open_time" class="admin-time-input admin-border">
            </div>
            <p class="form__error">
                @error('open_time')
                    {{ $message }}
                @enderror
            </p>
            <div class="input-group-time">
                <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
                <label for="close_time" class="admin-time-label label_close_time">クローズ</label>
                <input type="time" id="close_time" name="close_time" class="admin-time-input admin-border">
            </div>
            <p class="form__error">
                @error('close_time')
                    {{ $message }}
                @enderror
            </p>
            <button class="admin-button new-register-button" type="submit">新店舗登録</button>
        </form>
    </div>
</div>
<script>
document.getElementById('image').addEventListener('change', function() {
    var fileName = this.files[0].name;
    var fileLabel = document.getElementById('file-name');
    fileLabel.textContent = fileName;
});
</script>
@endsection
