@extends('layouts.app')

@section('content')
    <h1>My Profile</h1>
    <p>Welcome, {{ Auth::user()->name }}!</p>

    <h2>My Reservations</h2>
    <ul>
        @foreach ($reservations as $reservation)
            <li>{{ $reservation->date }} - {{ $reservation->time }} - {{ $reservation->status }}</li>
        @endforeach
    </ul>
@endsection