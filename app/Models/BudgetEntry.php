<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BudgetEntry extends Model
{
    // user_id is never mass-assignable: it is set from the logged-in account.
    protected $fillable = [
        'description', 'amount', 'type', 'kind', 'category', 'entry_date', 'is_paid',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'amount'     => 'decimal:2',
            'is_paid'    => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Validation rules shared by the user and admin controllers. */
    public static function rules(): array
    {
        return [
            'type'        => ['required', Rule::in(['income', 'expense'])],
            'description' => ['required', 'string', 'max:150'],
            'amount'      => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'entry_date'  => ['required', 'date'],
            'kind'        => ['nullable', 'required_if:type,expense', Rule::in(['need', 'want'])],
            'category'    => ['nullable', 'string', 'max:60'],
        ];
    }

    /** Applies the business rules after validation. */
    public static function clean(array $data, Request $request): array
    {
        $data['kind']     = $data['kind'] ?? null;
        $data['category'] = $data['category'] ?? null;
        $data['is_paid']  = $request->boolean('is_paid');

        if ($data['type'] === 'income') {
            $data['kind'] = null;
            $data['category'] = null;
            $data['is_paid'] = true;
        }

        return $data;
    }
}