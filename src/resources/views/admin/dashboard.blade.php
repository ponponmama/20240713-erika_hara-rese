@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection


@section('content')
<div class="admin_container">
    <p class="user__name">お疲れ様です！　{{ Auth::user()->user_name }}さん</p>
    <span class="registration-text">店舗管理者登録はこちら</span>
    <div class="registration-form">
        <div class="title-box">
            <h2 class="form-title">Shop Manager Registration</h2>
        </div>
        <form action="{{ route('admin.create.shop_manager') }}" method="POST" class="create-form">
            @csrf
            <div class="icon-container">
                <img src="{{ asset('images/human.png') }}" alt="">
            </div>
            <div class="form-group">
                <input type="text" id="user_name" name="user_name" placeholder="Username" value="{{ old('user_name') }}">
            </div>
            <div class="form__error">
                @error('user_name')
                    {{ $message }}
                @enderror
            </div>
            <div class="icon-container">
                <img src="{{ asset('images/mail.png') }}" alt="">
            </div>
            <div class="form-group">
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
            </div>
            <div class="form__error">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
            <div class="icon-container">
                <img src="{{ asset('images/key.png') }}" alt="">
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Password" value="{{ old('password') }}">
            </div>
            <div class="form__error">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
            <div class="button-container">
                <button class=" register-button" type="submit">ShopManager登録</button>
            </div>
        </form>
    </div>
</div>
@endsection