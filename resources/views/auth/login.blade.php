@extends('layouts.app')
@section('title', 'Log in - Budget Tracker')

@section('content')
<div class="login-wrap">
    <div class="card">
        <div class="center">
            <div class="big-emoji">💰</div>
            <h2>Welcome back!</h2>
            <p class="hint">Log in to see your bills and savings.</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            </label>
            <label>Password
                <input type="password" name="password" placeholder="Your password" required>
            </label>
            <button type="submit" class="btn btn-block">Log in</button>
        </form>

        <p class="hint center" style="margin:18px 0 0">
            New here? <a href="{{ route('register') }}"><b>Create an account</b></a>
        </p>
    </div>
</div>
@endsection