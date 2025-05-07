@extends('layouts.auth_app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/done.css') }}">
@endsection

@section('content')
    <div class="thanks-form">
        <h2 class="form-thanks-title">ご予約ありがとうございます</h2>
        <div class="thanks-button-container">
            <a href="{{ route('shop.returnFromDone', ['id' => session('last_visited_shop_id')]) }}"
                class="button back-link">戻る</a>
        </div>
    </div>
@endsection