@extends('layouts.app')
@section('title', 'Home')

@section('content')
@php $first = \Illuminate\Support\Str::before(auth()->user()->name, ' '); @endphp

<div class="hero">
    <div>
        <h1>Hello, {{ $first }}! 👋</h1>
        <p>
            @if ($income <= 0)
                Add the money you received this month to get started.
            @elseif ($saved > 0)
                Great job! You saved ₱{{ number_format($saved, 2) }} in {{ $label }}. 🎉
            @elseif ($saved == 0)
                You spent exactly what you earned in {{ $label }}.
            @else
                You spent ₱{{ number_format(abs($saved), 2) }} more than you earned in {{ $label }}. Let's plan the next one together.
            @endif
        </p>
    </div>
    <a class="btn" href="{{ route('entries.create') }}">+ Add money in or out</a>
</div>

<div class="monthbar">
    <a class="btn btn-ghost" href="{{ route('dashboard', ['month' => $prev]) }}">‹ Before</a>
    <strong>{{ $label }}</strong>
    <a class="btn btn-ghost" href="{{ route('dashboard', ['month' => $next]) }}">After ›</a>
    <a class="btn btn-soft" href="{{ route('dashboard') }}">This month</a>
</div>

<div class="stats">
    <div class="stat s-green">
        <div class="num">₱{{ number_format($income, 2) }}</div>
        <div class="lbl">💵 Money in</div>
    </div>
    <div class="stat s-red">
        <div class="num">₱{{ number_format($spent, 2) }}</div>
        <div class="lbl">🧾 Already spent</div>
    </div>
    <div class="stat {{ $saved >= 0 ? 's-teal' : 's-red' }}">
        <div class="num">₱{{ number_format($saved, 2) }}</div>
        <div class="lbl">{{ $saved >= 0 ? '🐷 Saved this month' : '😟 Spent too much' }}</div>
    </div>
    <div class="stat s-amber">
        <div class="num">₱{{ number_format($unpaidTotal, 2) }}</div>
        <div class="lbl">⏳ Still to pay or buy</div>
    </div>
</div>

<div class="card">
    <h3>Where did your money go?</h3>
    <p class="hint" style="margin:0">Needs are things you must have. Wants are treats.</p>

    <div class="bar-label">
        <span>🧺 Needs</span>
        <span>₱{{ number_format($needs, 2) }} · {{ $needsPct }}% of your money in</span>
    </div>
    <div class="bar"><span class="bar-need" style="width:{{ $needsPct }}%"></span></div>

    <div class="bar-label">
        <span>🎁 Wants</span>
        <span>₱{{ number_format($wants, 2) }} · {{ $wantsPct }}% of your money in</span>
    </div>
    <div class="bar"><span class="bar-want" style="width:{{ $wantsPct }}%"></span></div>

    <div class="bar-label">
        <span>🐷 Saved</span>
        <span>₱{{ number_format(max(0, $saved), 2) }} · {{ $savedPct }}% of your money in</span>
    </div>
    <div class="bar"><span class="bar-save" style="width:{{ $savedPct }}%"></span></div>

    @if ($income <= 0)
        <p class="hint" style="margin:16px 0 0">Add some money in to see the percentages.</p>
    @endif
</div>

<div class="section-head">
    <h3>🧺 Needs to pay or buy</h3>
    <a class="btn btn-soft" href="{{ route('entries.create', ['kind' => 'need', 'planned' => 1]) }}">+ Add a need</a>
</div>
@forelse ($toBuyNeeds as $e)
    @include('entries._item', ['e' => $e])
@empty
    <div class="empty">🌸 Nothing to pay for needs right now.</div>
@endforelse

<div class="section-head">
    <h3>🎁 Wants to pay or buy</h3>
    <a class="btn btn-soft" href="{{ route('entries.create', ['kind' => 'want', 'planned' => 1]) }}">+ Add a want</a>
</div>
@forelse ($toBuyWants as $e)
    @include('entries._item', ['e' => $e])
@empty
    <div class="empty">🌸 No wants on your list.</div>
@endforelse
@endsection