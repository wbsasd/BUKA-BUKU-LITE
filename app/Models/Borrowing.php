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
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
        'borrow_date' => 'datetime',
        'return_date' => 'datetime',
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
     * Calculate fine for late return
     * Fine is Rp5,000 per day if overdue
     */
    public function getFineAttribute()
    {
        // Use returned_at if already returned, otherwise use now()
        $checkDate = $this->returned_at ?? now();

        // If not yet due, no fine
        if ($checkDate <= $this->due_date) {
            return 0;
        }

        // Calculate days late
        $daysLate = $this->due_date->diffInDays($checkDate);
        
        return $daysLate * 5000;
    }
}

