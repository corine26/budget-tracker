@extends('layouts.app')
@section('title', 'Requests')

@section('content')
<div class="row" style="align-items:center; margin-bottom:16px">
    <h2>📝 {{ auth()->user()->isAdmin() ? 'All Requests' : 'My Requests' }}</h2>
    @can('create', \App\Models\ServiceRequest::class)
        <a class="btn" href="{{ route('requests.create') }}">+ New request</a>
    @endcan
</div>

<form method="GET" action="{{ route('requests.index') }}" style="display:flex; gap:8px; margin-bottom:18px">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for an item..." style="margin-top:0">
    <button type="submit" class="btn btn-soft">Search</button>
</form>

@forelse ($requests as $r)
    <div class="item k-need">
        <div class="row" style="align-items:center">
            <div>
                <div class="title">#{{ $r->id }} {{ $r->item_name }}</div>
                <div class="tags">
                    <span class="tag {{ $r->status === 'approved' ? 'done' : 'pending' }}">{{ ucfirst($r->status) }}</span>
                </div>
                <div class="meta">
                    Quantity: {{ $r->quantity }}
                    @if (auth()->user()->isAdmin()) · Owner ID {{ $r->user_id }} @endif
                </div>
            </div>
            <a class="btn btn-soft" href="{{ route('requests.show', $r) }}">View</a>
        </div>
    </div>
@empty
    <div class="empty">🌸 No requests found.</div>
@endforelse

{{ $requests->links('pagination::simple-default') }}
@endsection