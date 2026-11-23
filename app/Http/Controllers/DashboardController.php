<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $month = (string) $request->query('month', '');
        if (! preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {
            $month = now()->format('Y-m');
        }

        $start = Carbon::createFromFormat('Y-m-d', $month . '-01')->startOfDay();
        $end   = $start->copy()->endOfMonth();

        $entries = $user->budgetEntries()
            ->whereBetween('entry_date', [$start->toDateString(), $end->toDateString()])
            ->get();

        $income = (float) $entries->where('type', 'income')->sum('amount');
        $paid   = $entries->where('type', 'expense')->where('is_paid', true);
        $spent  = (float) $paid->sum('amount');
        $needs  = (float) $paid->where('kind', 'need')->sum('amount');
        $wants  = (float) $paid->where('kind', 'want')->sum('amount');
        $saved  = $income - $spent;

        // Everything not paid yet (any month): the "to pay / to buy" list.
        $toBuy = $user->budgetEntries()
            ->where('type', 'expense')
            ->where('is_paid', false)
            ->orderBy('entry_date')
            ->get();

        $pct = fn (float $v): int => $income > 0 ? (int) min(100, round($v / $income * 100)) : 0;

        return view('dashboard', [
            'month'       => $month,
            'label'       => $start->format('F Y'),
            'prev'        => $start->copy()->subMonth()->format('Y-m'),
            'next'        => $start->copy()->addMonth()->format('Y-m'),
            'income'      => $income,
            'spent'       => $spent,
            'saved'       => $saved,
            'needs'       => $needs,
            'wants'       => $wants,
            'needsPct'    => $pct($needs),
            'wantsPct'    => $pct($wants),
            'savedPct'    => $pct(max(0, $saved)),
            'unpaidTotal' => (float) $toBuy->sum('amount'),
            'toBuyNeeds'  => $toBuy->where('kind', 'need')->values(),
            'toBuyWants'  => $toBuy->where('kind', 'want')->values(),
        ]);
    }
}