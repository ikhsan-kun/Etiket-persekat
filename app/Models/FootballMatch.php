<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FootballMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'opponent',
        'opponent_logo',
        'match_date',
        'location',
        'description',
        'banner_image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'match_date' => 'datetime',
        ];
    }

    /**
     * Get ticket categories for this match.
     */
    public function ticketCategories(): HasMany
    {
        return $this->hasMany(TicketCategory::class, 'match_id');
    }

    /**
     * Scope for published matches only.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for upcoming matches.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('match_date', '>=', now())
                     ->orderBy('match_date', 'asc');
    }

    /**
     * Check if tickets are available.
     */
    public function hasAvailableTickets(): bool
    {
        return $this->ticketCategories->contains(function ($category) {
            return $category->availableQuota() > 0;
        });
    }

    /**
     * Get lowest ticket price.
     */
    public function getLowestPriceAttribute(): float
    {
        return $this->ticketCategories->min('price') ?? 0;
    }

    /**
     * Get total available tickets count.
     */
    public function getTotalAvailableAttribute(): int
    {
        return $this->ticketCategories->sum(function ($category) {
            return $category->availableQuota();
        });
    }
}
