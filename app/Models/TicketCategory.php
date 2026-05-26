<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'name',
        'price',
        'quota',
        'sold',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quota' => 'integer',
            'sold' => 'integer',
        ];
    }

    /**
     * Get the match this category belongs to.
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    /**
     * Get order items for this category.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get remaining available quota.
     */
    public function availableQuota(): int
    {
        return max(0, $this->quota - $this->sold);
    }

    /**
     * Check if stock is available for given quantity.
     */
    public function isAvailable(int $quantity = 1): bool
    {
        return $this->availableQuota() >= $quantity;
    }
}
