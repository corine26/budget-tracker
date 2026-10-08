<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login - Budget Tracker</title>
</head>
<body>
    <h1>Budget Tracker - Login</h1>
    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <p><label>Email<br><input type="email" name="email" value="{{ old('email') }}"></label></p>
        <p><label>Password<br><input type="password" name="password"></label></p>
        <button type="submit">Log in</button>
    </form>
</body>
</html>