@extends('layouts.app')
@section('title', 'New Request')

@section('content')
<div class="card">
    <h2 style="margin-bottom:16px">📝 New request</h2>

    <form method="POST" action="{{ route('requests.store') }}">
        @csrf
        <label>Item name
            <input type="text" name="item_name" value="{{ old('item_name') }}">
        </label>
        <label>Quantity
            <input type="text" name="quantity" value="{{ old('quantity') }}">
        </label>
        <label>Why do you need it?
            <textarea name="purpose" rows="4">{{ old('purpose') }}</textarea>
        </label>
        <button type="submit" class="btn">Send request</button>
        <a class="btn btn-ghost" href="{{ route('requests.index') }}">Cancel</a>
    </form>
</div>
@endsection