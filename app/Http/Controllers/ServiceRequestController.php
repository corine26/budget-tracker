<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ServiceRequestController extends Controller
{
    // GET /requests
    public function index(Request $request)
    {
        Gate::authorize('viewAny', ServiceRequest::class);

        $user = $request->user();
        $query = ServiceRequest::query();

        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id); // scope by account ID, not name/email
        }

        if ($request->filled('q')) {
            $query->where('item_name', 'like', '%' . (string) $request->input('q') . '%');
        }

        $requests = $query->latest('id')->paginate(10)->withQueryString();

        return view('requests.index', ['requests' => $requests]);
    }

    // GET /requests/{serviceRequest}
    public function show(ServiceRequest $serviceRequest)
    {
        Gate::authorize('view', $serviceRequest);

        return view('requests.show', ['serviceRequest' => $serviceRequest]);
    }

    // GET /requests/create
    public function create()
    {
        Gate::authorize('create', ServiceRequest::class);

        return view('requests.create');
    }

    // POST /requests
    public function store(Request $request)
    {
        Gate::authorize('create', ServiceRequest::class);

        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:150'],
            'quantity'  => ['required', 'integer', 'min:1', 'max:4294967295'],
            'purpose'   => ['required', 'string', 'max:2000'],
            // Students may never supply these:
            'user_id'   => ['prohibited'],
            'status'    => ['prohibited'],
            'is_admin'  => ['prohibited'],
            'role'      => ['prohibited'],
        ]);

        $user = $request->user();

        // Explicit allowlist, never $request->all()
        $serviceRequest = new ServiceRequest(Arr::only($validated, ['item_name', 'quantity', 'purpose']));
        $serviceRequest->user_id = $user->id;
        $serviceRequest->requester_name = $user->name;
        $serviceRequest->requester_email = $user->email;
        $serviceRequest->status = 'pending';
        $serviceRequest->save();

        return redirect()->route('requests.show', $serviceRequest)
                         ->with('notice', 'Request submitted.');
    }

    // PATCH /requests/{serviceRequest}/status
    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('updateStatus', $serviceRequest); // before any write

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
        ]);

        $serviceRequest->status = $validated['status']; // only status changes
        $serviceRequest->save();

        return redirect()->route('requests.show', $serviceRequest)
                         ->with('notice', 'Status updated.');
    }
}