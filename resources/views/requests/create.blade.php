@extends('layouts.app')
@section('title', 'New Request')
@section('content')
<h1>New Request</h1>
@if ($errors->any())
    <ul style="color:red">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
<form method="POST" action="{{ route('requests.store') }}">
    @csrf
    <p><label>Item name<br><input type="text" name="item_name" value="{{ old('item_name') }}" size="40"></label></p>
    <p><label>Quantity<br><input type="text" name="quantity" value="{{ old('quantity') }}"></label></p>
    <p><label>Purpose<br><textarea name="purpose" rows="4" cols="50">{{ old('purpose') }}</textarea></label></p>
    <button type="submit">Submit request</button>
</form>
@endsection