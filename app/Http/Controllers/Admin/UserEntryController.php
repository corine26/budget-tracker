<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetEntry;
use App\Models\User;
use Illuminate\Http\Request;

class UserEntryController extends Controller
{
    public function create(User $user)
    {
        return view('entries.form', [
            'entry'  => new BudgetEntry(['entry_date' => today()]),
            'title'  => 'Add entry for ' . $user->name,
            'action' => route('admin.entries.store', $user),
            'method' => 'POST',
            'cancel' => route('admin.users.show', $user),
        ]);
    }

    public function store(Request $request, User $user)
    {
        $data = BudgetEntry::clean($request->validate(BudgetEntry::rules()), $request);

        $user->budgetEntries()->create($data);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Entry added for ' . $user->name . '.');
    }

    public function edit(BudgetEntry $entry)
    {
        return view('entries.form', [
            'entry'  => $entry,
            'title'  => 'Edit entry',
            'action' => route('admin.entries.update', $entry),
            'method' => 'PUT',
            'cancel' => route('admin.users.show', $entry->user_id),
        ]);
    }

    public function update(Request $request, BudgetEntry $entry)
    {
        $data = BudgetEntry::clean($request->validate(BudgetEntry::rules()), $request);
        $entry->update($data);

        return redirect()->route('admin.users.show', $entry->user_id)
            ->with('success', 'Entry updated.');
    }

    public function destroy(BudgetEntry $entry)
    {
        $entry->delete();

        return back()->with('success', 'Entry deleted.');
    }

    public function togglePaid(BudgetEntry $entry)
    {
        if ($entry->type === 'expense') {
            $entry->is_paid = ! $entry->is_paid;
            $entry->save();
        }

        return back();
    }
}