@extends('layouts.app')
@section('title', 'Requests')
@section('content')
<h1>{{ auth()->user()->isAdmin() ? 'All Requests' : 'My Requests' }}</h1>

<form method="GET" action="{{ route('requests.index') }}">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search item">
    <button type="submit">Search</button>
</form>
<br>
<table>
    <tr>
        <th>ID</th>
        @if (auth()->user()->isAdmin()) <th>Owner ID</th> @endif
        <th>Item</th><th>Qty</th><th>Status</th><th></th>
    </tr>
    @forelse ($requests as $r)
        <tr>
            <td>{{ $r->id }}</td>
            @if (auth()->user()->isAdmin()) <td>{{ $r->user_id }}</td> @endif
            <td>{{ $r->item_name }}</td>
            <td>{{ $r->quantity }}</td>
            <td>{{ $r->status }}</td>
            <td><a href="{{ route('requests.show', $r) }}">View</a></td>
        </tr>
    @empty
        <tr><td colspan="6">No requests found.</td></tr>
    @endforelse
</table>
{{ $requests->links('pagination::simple-default') }}
@endsection