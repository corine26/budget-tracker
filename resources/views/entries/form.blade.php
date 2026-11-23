@extends('layouts.app')
@section('title', $title)

@section('content')
@php
    $type = old('type', $entry->type ?? 'expense');
    $kind = old('kind', $entry->kind ?? request('kind', 'need'));
    $paid = old()
        ? (bool) old('is_paid')
        : ($entry->exists ? (bool) $entry->is_paid : ! request()->boolean('planned'));
@endphp

<div class="card">
    <h2 style="margin-bottom:16px">{{ $title }}</h2>

    <form method="POST" action="{{ $action }}">
        @csrf
        @if ($method !== 'POST') @method($method) @endif

        <label>Is the money coming in or going out?
            <select name="type" id="type">
                <option value="expense" @selected($type === 'expense')>Going out (a bill or something I bought)</option>
                <option value="income" @selected($type === 'income')>Coming in (salary, allowance, gift)</option>
            </select>
        </label>

        <label>What is it for?
            <input type="text" name="description" value="{{ old('description', $entry->description) }}"
                   placeholder="e.g. Electric bill, Salary, Groceries" required>
        </label>

        <div class="grid2">
            <label>How much?
                <input type="number" name="amount" step="0.01" min="0.01"
                       value="{{ old('amount', $entry->amount) }}" required>
            </label>
            <label>When?
                <input type="date" name="entry_date"
                       value="{{ old('entry_date', optional($entry->entry_date)->format('Y-m-d')) }}" required>
            </label>
        </div>

        <div id="expenseOnly">
            <div class="grid2">
                <label>Is it a need or a want?
                    <select name="kind">
                        <option value="need" @selected($kind === 'need')>🧺 Need (must have)</option>
                        <option value="want" @selected($kind === 'want')>🎁 Want (a treat)</option>
                    </select>
                </label>
                <label>Group (optional)
                    <input type="text" name="category" value="{{ old('category', $entry->category) }}"
                           placeholder="e.g. Rent, Food, School">
                </label>
            </div>

            <label style="font-weight:600">
                <input type="checkbox" name="is_paid" value="1" @checked($paid)>
                I already paid for this
                <span class="hint" style="display:block; margin:4px 0 0 30px">
                    Untick it to keep it on your "to pay or buy" list.
                </span>
            </label>
        </div>

        <button type="submit" class="btn">Save</button>
        <a class="btn btn-ghost" href="{{ $cancel }}">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const typeSelect = document.getElementById('type');
    const expenseOnly = document.getElementById('expenseOnly');
    function syncType() {
        expenseOnly.style.display = typeSelect.value === 'expense' ? 'block' : 'none';
    }
    typeSelect.addEventListener('change', syncType);
    syncType();
</script>
@endpush