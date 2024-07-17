@extends('layouts.app')

@section('content')
    <h1>My Profile</h1>
    <p>Welcome, {{ Auth::user()->name }}!</p>
@endsection