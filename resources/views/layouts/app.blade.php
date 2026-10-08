<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Budget Tracker')</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        nav { border-bottom: 1px solid #ccc; padding-bottom: .5rem; margin-bottom: 1rem; }
        .notice { background: #e6ffe6; padding: .5rem; }
        table { border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 6px 10px; }
    </style>
</head>
<body>
<nav>
    <strong>Budget Tracker</strong> |
    <a href="{{ route('requests.index') }}">Requests</a>
    @can('create', \App\Models\ServiceRequest::class)
        | <a href="{{ route('requests.create') }}">New Request</a>
    @endcan
    <span style="float:right">
        {{ auth()->user()->name }} ({{ auth()->user()->role }})
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </span>
</nav>
@if (session('notice'))
    <p class="notice">{{ session('notice') }}</p>
@endif
@yield('content')
</body>
</html>