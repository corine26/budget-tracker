@extends('layouts.app')
@section('title', 'Users')

@section('content')
<h2>👥 Users</h2>
<p class="hint" style="margin:4px 0 18px">Tap a person to see and manage their money entries.</p>

@forelse ($users as $u)
    <a href="{{ route('admin.users.show', $u) }}" style="display:block; color:inherit">
        <div class="item k-need">
            <div class="row" style="align-items:center">
                <div style="display:flex; gap:14px; align-items:center">
                    <div class="ico">{{ $u->role === 'admin' ? '⭐' : '🙂' }}</div>
                    <div>
                        <div class="title">{{ $u->name }}</div>
                        <div class="meta">{{ $u->email }}</div>
                    </div>
                </div>
                <div>
                    <span class="tag {{ $u->role === 'admin' ? 'done' : 'need' }}">{{ ucfirst($u->role ?? 'user') }}</span>
                    <span class="meta">{{ $u->budget_entries_count }} entries ›</span>
                </div>
            </div>
        </div>
    </a>
@empty
    <div class="empty">🌸 No users found.</div>
@endforelse
@endsection