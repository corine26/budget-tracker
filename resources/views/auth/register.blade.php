@extends('layouts.app')
@section('title', 'Sign up - Budget Tracker')

@section('content')
<div class="login-wrap">
    <div class="card">
        <div class="center">
            <div class="big-emoji">🐷</div>
            <h2>Create your account</h2>
            <p class="hint">It only takes a minute to start saving.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <label>Your name
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Maria Santos" required autofocus>
            </label>
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
            </label>
            <label>Password (at least 8 characters)
                <input type="password" name="password" required>
            </label>
            <label>Type your password again
                <input type="password" name="password_confirmation" required>
            </label>
            <button type="submit" class="btn btn-block">Create my account</button>
        </form>

        <p class="hint center" style="margin:18px 0 0">
            Already have an account? <a href="{{ route('login') }}"><b>Log in</b></a>
        </p>
    </div>
</div>
@endsection