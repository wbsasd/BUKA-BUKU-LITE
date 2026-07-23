<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'duration',
        'price',
        'payment_method',
        'status',
        'borrowed_at',
        'due_date',
        'returned_at',
        'borrow_date',
        'return_date',
        'warning_sent',
        'warning_sent_at',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
        'borrow_date' => 'datetime',
        'return_date' => 'datetime',
        'warning_sent_at' => 'datetime',
        'warning_sent' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Calculate days overdue
     */
    public function getDaysLateAttribute()
    {
        // Returned books are not treated as late in this app flow.
        if ($this->status === 'returned') {
            return 0;
        }

        $dueDate = $this->due_date;
        if (!$dueDate instanceof Carbon) {
            return 0;
        }

        $checkDate = $this->returned_at ?? $this->return_date ?? now();

        // If not yet due, no days late
        if ($checkDate->lessThanOrEqualTo($dueDate)) {
            return 0;
        }

        return (int) $dueDate->diffInDays($checkDate);
    }

    /**
     * Calculate fine - Rp2,000 per day if overdue
     * Used in admin dashboard for monitoring
     */
    public function getFineAttribute()
    {
        if ($this->status === 'returned') {
            return 0;
        }

        $dueDate = $this->due_date;
        if (!$dueDate instanceof Carbon) {
            return 0;
        }

        $checkDate = $this->returned_at ?? $this->return_date ?? now();

        // If not yet due, no fine
        if ($checkDate->lessThanOrEqualTo($dueDate)) {
            return 0;
        }

        // Calculate days late
        $daysLate = (int) $dueDate->diffInDays($checkDate);

        return $daysLate * 2000;
    }

    /**
     * Calculate borrowed days safely.
     * - If return date is null, use now().
     * - If borrow date is null, return 0.
     */
    public function getBorrowedDaysAttribute(): int
    {
        $startDate = $this->borrow_date ?? $this->borrowed_at;
        if (!$startDate instanceof Carbon) {
            return 0;
        }

        $endDate = $this->return_date ?? $this->returned_at ?? now();
        $days = (int) $startDate->diffInDays($endDate, false);

        return max(0, $days);
    }

    /**
     * Get the actual status considering overdue
     * Returns: paid (Dipinjam), overdue (Jatuh Tempo), returned (Sudah Dikembalikan)
     */
    public function getActualStatusAttribute()
    {
        if ($this->status === 'returned') {
            return 'returned';
        }

        $dueDate = $this->due_date;
        if ($this->status === 'paid' && $dueDate instanceof Carbon && now()->greaterThan($dueDate)) {
            return 'overdue';
        }

        return $this->status;
    }
}

