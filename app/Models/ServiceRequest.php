<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequest extends Model
{
    protected $table = 'requests';

    // Only fields a student may supply. user_id, requester_*, status are set server-side.
    protected $fillable = ['item_name', 'quantity', 'purpose'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}