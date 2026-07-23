<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'membership_status',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bookReviews()
    {
        return $this->hasMany(BookReview::class);
    }

    public function membershipUpgrades()
    {
        return $this->hasMany(MembershipUpgrade::class);
    }

    /**
     * Compatibility-first premium access guard.
     *
     * Priority checks:
     * 1) users.membership_status = active
     * 2) users.role = premium
     * 3) latest membership_upgrades status approved/active and not expired
     */
    public function hasPremiumAccess(): bool
    {
        if (($this->membership_status ?? null) === 'active') {
            return true;
        }

        if (($this->role ?? null) === 'premium') {
            return true;
        }

        $latestUpgrade = $this->membershipUpgrades()
            ->orderByDesc('approved_at')
            ->orderByDesc('requested_at')
            ->first();

        if (!$latestUpgrade) {
            return false;
        }

        $status = strtolower((string) ($latestUpgrade->status ?? ''));
        if (!in_array($status, ['approved', 'active'], true)) {
            return false;
        }

        // Backward compatibility: old approved records may not have end_date.
        if (empty($latestUpgrade->end_date)) {
            return true;
        }

        $endDateRaw = $latestUpgrade->end_date;
        $endDate = $endDateRaw instanceof CarbonInterface
            ? Carbon::instance($endDateRaw)
            : Carbon::parse($endDateRaw);

        return $endDate->startOfDay()->greaterThanOrEqualTo(now()->startOfDay());
    }
}

