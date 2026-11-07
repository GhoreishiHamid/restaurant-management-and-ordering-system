@extends('layouts.restaurant')
@section('title', 'Register')
@section('content')
<div class="auth-card">
    <div class="eyebrow">GET STARTED</div>
    <h1>Create account</h1>
    <form method="post" action="{{ route('register') }}" class="form-stack">
        @csrf
        <label for="name">Full name</label><input id="name" name="name" type="text" value="{{ old('name') }}" maxlength="255" required>
        <label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required>
        <label for="password">Password (8+ characters)</label><input id="password" name="password" type="password" minlength="8" required>
        <label for="password_confirmation">Confirm password</label><input id="password_confirmation" name="password_confirmation" type="password" minlength="8" required>
        <button class="button" type="submit">Register</button>
    </form>
    <p>Already registered? <a href="{{ route('login') }}">Log in</a></p>
</div>
@endsection
