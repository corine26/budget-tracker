@php
    $admin = $admin ?? false;
    $cls   = $e->type === 'income' ? 'k-income' : 'k-' . ($e->kind ?? 'need');
    $icon  = $e->type === 'income' ? '💵' : ($e->kind === 'want' ? '🎁' : '🧺');
@endphp
<div class="item {{ $cls }} {{ $e->type === 'expense' && ! $e->is_paid ? 'unpaid' : '' }}">
    <div class="row">
        <div style="display:flex; gap:14px; align-items:flex-start">
            <div class="ico {{ $cls }}">{{ $icon }}</div>
            <div>
                <div class="title">{{ $e->description }}</div>
                <div class="tags">
                    <span class="tag {{ $e->type }}">{{ $e->type === 'income' ? 'Money in' : 'Money out' }}</span>
                    @if ($e->kind)
                        <span class="tag {{ $e->kind }}">{{ ucfirst($e->kind) }}</span>
                    @endif
                    @if ($e->type === 'expense')
                        <span class="tag {{ $e->is_paid ? 'paid' : 'unpaid' }}">{{ $e->is_paid ? 'Paid ✓' : 'Not paid yet' }}</span>
                    @endif
                </div>
                <div class="meta">
                    📅 {{ $e->entry_date->format('M d, Y') }}
                    @if ($e->category) &nbsp;·&nbsp; 🏷 {{ $e->category }} @endif
                </div>
            </div>
        </div>

        <div>
            <div class="money {{ $e->type }}">
                {{ $e->type === 'income' ? '+' : '-' }}₱{{ number_format((float) $e->amount, 2) }}
            </div>
            <div class="actions">
                @if ($e->type === 'expense')
                    <form method="POST" action="{{ $admin ? route('admin.entries.paid', $e) : route('entries.paid', $e) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-green" type="submit">{{ $e->is_paid ? 'Undo paid' : 'Mark as paid' }}</button>
                    </form>
                @endif
                <a class="btn btn-ghost" href="{{ $admin ? route('admin.entries.edit', $e) : route('entries.edit', $e) }}">Edit</a>
                <form method="POST" action="{{ $admin ? route('admin.entries.destroy', $e) : route('entries.destroy', $e) }}"
                      onsubmit="return confirm('Delete this entry?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-red" type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>