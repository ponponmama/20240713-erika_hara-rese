@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('users_css/done.css') }}">
@endsection

@section('content')
    <div class="thanks-form">
        <h2 class="form-thanks-title">ご予約ありがとうございます</h2>
        <div class="thanks-button-container">
            <a href="{{ route('shop.returnFromDone', ['id' => session('last_visited_shop_id')]) }}"
                class="back-link link">戻る</a>
        </div>
    </div>
@endsection
