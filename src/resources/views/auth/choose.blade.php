@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('users_css/choose.css') }}">
@endsection

@section('content')
<div class="choose-container">
    <div class="choose-section">
    <p class="text-2">ご予約には登録またはログインが必要です</p>
    <p class="text">ログインまたは登録お願いします</p>
        <div class="link-buttons-container">
            <a href="{{ route('login') }}" class="login-link link">ログイン</a>
            <a href="{{ route('register') }}" class="register-link link">新規登録</a>
        </div>
    </div>
</div>
@endsection
