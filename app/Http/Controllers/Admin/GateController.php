<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ETicket;
use Illuminate\Http\Request;

class GateController extends Controller
{
    /**
     * Show the gate validation page (QR scanner).
     */
    public function index()
    {
        return view('admin.gate.index');
    }

    /**
     * Validate a ticket by code.
     */
    public function validateTicket(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        $ticket = ETicket::with(['order.user', 'orderItem.ticketCategory.match'])
            ->where('ticket_code', $request->ticket_code)
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan. Kode tidak valid.',
            ], 404);
        }

        if ($ticket->is_used) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah digunakan pada ' . $ticket->used_at->format('d M Y H:i'),
                'ticket' => $this->formatTicketData($ticket),
            ], 422);
        }

        if (!$ticket->order->isPaid()) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket belum lunas. Status: ' . strtoupper($ticket->order->status),
            ], 422);
        }

        // Mark as used
        $ticket->markAsUsed();

        return response()->json([
            'success' => true,
            'message' => 'Tiket VALID! Silakan masuk.',
            'ticket' => $this->formatTicketData($ticket),
        ]);
    }

    /**
     * Format ticket data for JSON response.
     */
    private function formatTicketData(ETicket $ticket): array
    {
        return [
            'ticket_code' => $ticket->ticket_code,
            'holder_name' => $ticket->order->user->name ?? '-',
            'match' => 'Persekat vs ' . ($ticket->orderItem->ticketCategory->match->opponent ?? '-'),
            'category' => $ticket->orderItem->ticketCategory->name ?? '-',
            'match_date' => $ticket->orderItem->ticketCategory->match->match_date->format('d M Y, H:i') ?? '-',
            'is_used' => $ticket->is_used,
            'used_at' => $ticket->used_at?->format('d M Y H:i'),
        ];
    }
}
