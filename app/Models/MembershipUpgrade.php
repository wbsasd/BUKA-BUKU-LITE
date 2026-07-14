<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MembershipUpgrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'months',
        'amount',
        'payment_status',
        'payment_method',
        'status',
        'requested_at',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function isPendingForAdmin(): bool
    {
        return $this->status === 'pending';
    }

    public static function planKey(int $months): string
    {
        return match ($months) {
            3 => '3m',
            6 => '6m',
            12 => '12m',
            default => Str::of((string)$months)->replace(' ', ''),
        };
    }
}

