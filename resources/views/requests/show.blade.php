@extends('layouts.app')
@section('title', 'Request #' . $serviceRequest->id)
@section('content')
<h1>Request #{{ $serviceRequest->id }}</h1>
<p><strong>Requester:</strong> {{ $serviceRequest->requester_name }} ({{ $serviceRequest->requester_email }})</p>
<p><strong>Item:</strong> {{ $serviceRequest->item_name }}</p>
<p><strong>Quantity:</strong> {{ $serviceRequest->quantity }}</p>
<p><strong>Purpose:</strong> {{ $serviceRequest->purpose }}</p>
<p><strong>Status:</strong> {{ $serviceRequest->status }}</p>
<p><strong>Created:</strong> {{ $serviceRequest->created_at }}</p>

@can('updateStatus', $serviceRequest)
    @error('status')
        <p style="color:red">{{ $message }}</p>
    @enderror
    <form method="POST" action="{{ route('requests.updateStatus', $serviceRequest) }}">
        @csrf
        @method('PATCH')
        <label>Update status
            <select name="status">
                @foreach (['pending', 'approved', 'rejected'] as $s)
                    <option value="{{ $s }}" @selected($serviceRequest->status === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </label>
        <button type="submit">Save</button>
    </form>
@endcan

<p><a href="{{ route('requests.index') }}">Back to list</a></p>
@endsection