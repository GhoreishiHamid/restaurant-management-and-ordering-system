@extends('layouts.restaurant')
@section('title', 'Log in')
@section('content')
<div class="auth-card">
    <div class="eyebrow">WELCOME BACK</div>
    <h1>Log in</h1>
    <form method="post" action="{{ route('login') }}" class="form-stack">
        @csrf
        <label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
        <label for="password">Password</label><input id="password" type="password" name="password" autocomplete="current-password" required>
        <button class="button" type="submit">Log in</button>
    </form>
    <p>New here? <a href="{{ route('register') }}">Create an account</a></p>
</div>
@endsection
