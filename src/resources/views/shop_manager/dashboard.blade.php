@extends('admin.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endsection

@section('content')
<div class="shop_container">
    <div class="name_folder">
        <h1 class="shop__name"> {{ Auth::user()->shop->shop_name }}　　お疲れ様です！{{ Auth::user()->user_name }}さん</h1>
    </div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="reservations">
        <h2 class="reservations-list">予約情報</h2>
        <table>
            <thead>
                <tr>
                    <th>予約日</th>
                    <th>時間</th>
                    <th>人数</th>
                    <th>予約ID</th>
                    <th>顧客名</th>
                    <th>メールアドレス</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->reservation_datetime->format('Y-m-d') }}</td>
                    <td>{{ $reservation->reservation_datetime->format('H:i') }}</td>
                    <td>{{ $reservation->number }}</td>
                    <td>{{ $reservation->id }}</td>
                    <td>{{ $reservation->user->user_name }}</td>
                    <td>{{ $reservation->user->email}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection