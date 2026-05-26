<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ETicket extends Model
{
    use HasFactory;

    protected $table = 'e_tickets';

    protected $fillable = [
        'order_id',
        'order_item_id',
        'ticket_code',
        'qr_code_data',
        'is_used',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'is_used' => 'boolean',
            'used_at' => 'datetime',
        ];
    }

    /**
     * Get the order this ticket belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the order item this ticket was generated from.
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Mark ticket as used (scanned at gate).
     */
    public function markAsUsed(): bool
    {
        if ($this->is_used) {
            return false; // Already used
        }

        $this->update([
            'is_used' => true,
            'used_at' => now(),
        ]);

        return true;
    }

    /**
     * Generate a unique ticket code.
     */
    public static function generateTicketCode(): string
    {
        do {
            $code = 'PRSK-' . strtoupper(bin2hex(random_bytes(6)));
        } while (static::where('ticket_code', $code)->exists());

        return $code;
    }
}
