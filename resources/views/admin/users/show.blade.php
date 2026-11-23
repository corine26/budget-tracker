@extends('layouts.app')
@section('title', $user->name)

@section('content')
<div class="row" style="align-items:center; margin-bottom:18px">
    <div>
        <h2>🙂 {{ $user->name }}</h2>
        <div class="hint" style="margin:4px 0 0">{{ $user->email }} · {{ $entries->count() }} entries</div>
    </div>
    <div class="actions">
        <a class="btn btn-ghost" href="{{ route('admin.users.index') }}">‹ All users</a>
        <a class="btn" href="{{ route('admin.entries.create', $user) }}">+ Add entry</a>
    </div>
</div>

<div class="stats">
    <div class="stat s-green"><div class="num">₱{{ number_format($income, 2) }}</div><div class="lbl">💵 Money in this month</div></div>
    <div class="stat s-red"><div class="num">₱{{ number_format($spent, 2) }}</div><div class="lbl">🧾 Spent this month</div></div>
    <div class="stat {{ $saved >= 0 ? 's-teal' : 's-red' }}">
        <div class="num">₱{{ number_format($saved, 2) }}</div>
        <div class="lbl">{{ $saved >= 0 ? '🐷 Saved this month' : '😟 Spent too much' }}</div>
    </div>
</div>

@forelse ($entries as $e)
    @include('entries._item', ['e' => $e, 'admin' => true])
@empty
    <div class="empty">🌸 This person has no entries yet.</div>
@endforelse
@endsection