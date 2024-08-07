@extends('admin.app')

@section('content')
<div class="shop_container">
    <p class="user__name">お疲れ様です！　{{ Auth::user()->user_name }}さん</p>
    <h1>{{ Auth::user()->shop->shop_name }} Manager Dashboard</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h1>予約情報</h1>
    <table>
        <thead>
            <tr>
                <th>予約ID</th>
                <th>顧客名</th>
                <th>予約日時</th>
                <th>人数</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations as $reservation)
            <tr>
                <td>{{ $reservation->id }}</td>
                <td>{{ $reservation->user->user_name }}</td>
                <td>{{ $reservation->reservation_datetime }}</td>
                <td>{{ $reservation->number }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection