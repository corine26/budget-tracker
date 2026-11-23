@extends('layouts.app')
@section('title', 'My Money')

@section('content')
<div class="row" style="align-items:center; margin-bottom:18px">
    <div>
        <h2>📒 My Money</h2>
        <div class="hint" style="margin:4px 0 0">Everything you earned and spent</div>
    </div>
    <a class="btn" href="{{ route('entries.create') }}">+ Add money in or out</a>
</div>

<div class="actions" style="justify-content:flex-start; margin-bottom:16px">
    <a class="btn {{ $type === null ? '' : 'btn-ghost' }}" href="{{ route('entries.index') }}">All</a>
    <a class="btn {{ $type === 'income' ? '' : 'btn-ghost' }}" href="{{ route('entries.index', ['type' => 'income']) }}">Money in</a>
    <a class="btn {{ $type === 'expense' ? '' : 'btn-ghost' }}" href="{{ route('entries.index', ['type' => 'expense']) }}">Money out</a>
</div>

@forelse ($entries as $e)
    @include('entries._item', ['e' => $e])
@empty
    <div class="empty">🌸 Nothing here yet. Tap "+ Add money in or out" to start.</div>
@endforelse

{{ $entries->links('pagination::simple-default') }}
@endsection