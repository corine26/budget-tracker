@extends('layouts.app')
@section('title', 'Request #' . $serviceRequest->id)

@section('content')
<div class="card">
    <h2 style="margin-bottom:14px">📝 Request #{{ $serviceRequest->id }}</h2>

    <p><b>From:</b> {{ $serviceRequest->requester_name }} ({{ $serviceRequest->requester_email }})</p>
    <p><b>Item:</b> {{ $serviceRequest->item_name }}</p>
    <p><b>Quantity:</b> {{ $serviceRequest->quantity }}</p>
    <p><b>Reason:</b> {{ $serviceRequest->purpose }}</p>
    <p><b>Status:</b>
        <span class="tag {{ $serviceRequest->status === 'approved' ? 'done' : 'pending' }}">
            {{ ucfirst($serviceRequest->status) }}
        </span>
    </p>
    <p class="hint"><b>Sent:</b> {{ $serviceRequest->created_at }}</p>

    @can('updateStatus', $serviceRequest)
        <form method="POST" action="{{ route('requests.updateStatus', $serviceRequest) }}" style="margin-top:18px">
            @csrf
            @method('PATCH')
            <label>Change the status
                <select name="status">
                    @foreach (['pending', 'approved', 'rejected'] as $s)
                        <option value="{{ $s }}" @selected($serviceRequest->status === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </label>
            <button type="submit" class="btn">Save status</button>
        </form>
    @endcan

    <p style="margin-top:18px"><a class="btn btn-ghost" href="{{ route('requests.index') }}">‹ Back to the list</a></p>
</div>
@endsection