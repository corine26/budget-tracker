<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('budgetEntries')->orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $entries = $user->budgetEntries()
            ->orderByDesc('entry_date')
            ->orderByDesc('id')
            ->get();

        $thisMonth = $user->budgetEntries()
            ->whereBetween('entry_date', [
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString(),
            ])->get();

        $income = (float) $thisMonth->where('type', 'income')->sum('amount');
        $spent  = (float) $thisMonth->where('type', 'expense')->where('is_paid', true)->sum('amount');

        return view('admin.users.show', [
            'user'    => $user,
            'entries' => $entries,
            'income'  => $income,
            'spent'   => $spent,
            'saved'   => $income - $spent,
        ]);
    }
}