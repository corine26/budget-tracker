<?php

namespace App\Http\Controllers;

use App\Models\BudgetEntry;
use Illuminate\Http\Request;

class BudgetEntryController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $query = $request->user()->budgetEntries();

        if (in_array($type, ['income', 'expense'], true)) {
            $query->where('type', $type);
        } else {
            $type = null;
        }

        $entries = $query->orderByDesc('entry_date')->orderByDesc('id')
            ->paginate(10)->withQueryString();

        return view('entries.index', compact('entries', 'type'));
    }

    public function create()
    {
        return view('entries.form', [
            'entry'  => new BudgetEntry(['entry_date' => today()]),
            'title'  => 'New entry',
            'action' => route('entries.store'),
            'method' => 'POST',
            'cancel' => route('entries.index'),
        ]);
    }

    public function store(Request $request)
    {
        $data = BudgetEntry::clean($request->validate(BudgetEntry::rules()), $request);

        $request->user()->budgetEntries()->create($data);

        return redirect()->route('entries.index')->with('success', 'Entry added.');
    }

    public function edit(BudgetEntry $entry)
    {
        $this->authorizeOwner($entry);

        return view('entries.form', [
            'entry'  => $entry,
            'title'  => 'Edit entry',
            'action' => route('entries.update', $entry),
            'method' => 'PUT',
            'cancel' => route('entries.index'),
        ]);
    }

    public function update(Request $request, BudgetEntry $entry)
    {
        $this->authorizeOwner($entry);

        $data = BudgetEntry::clean($request->validate(BudgetEntry::rules()), $request);
        $entry->update($data);

        return redirect()->route('entries.index')->with('success', 'Entry updated.');
    }

    public function destroy(BudgetEntry $entry)
    {
        $this->authorizeOwner($entry);
        $entry->delete();

        return back()->with('success', 'Entry deleted.');
    }

    public function togglePaid(BudgetEntry $entry)
    {
        $this->authorizeOwner($entry);

        if ($entry->type === 'expense') {
            $entry->is_paid = ! $entry->is_paid;
            $entry->save();
        }

        return back();
    }

    private function authorizeOwner(BudgetEntry $entry): void
    {
        abort_unless((int) $entry->user_id === (int) auth()->id(), 403);
    }
}