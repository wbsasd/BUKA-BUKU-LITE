<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        // If returned, use returned_at; otherwise use now()
        if ($this->status === 'returned') {
            return 0;
        }

        $checkDate = $this->returned_at ?? now();

        // If not yet due, no days late
        if ($checkDate <= $this->due_date) {
            return 0;
        }

        return $this->due_date->diffInDays($checkDate);
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

        $checkDate = $this->returned_at ?? now();

        // If not yet due, no fine
        if ($checkDate <= $this->due_date) {
            return 0;
        }

        // Calculate days late
        $daysLate = $this->due_date->diffInDays($checkDate);
        
        return $daysLate * 2000;
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

        if ($this->status === 'paid' && now() > $this->due_date) {
            return 'overdue';
        }

        return $this->status;
    }
}

