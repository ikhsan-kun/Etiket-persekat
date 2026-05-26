<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MatchController extends Controller
{
    /**
     * Display a listing of matches.
     */
    public function index(Request $request)
    {
        $query = FootballMatch::with('ticketCategories');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('opponent', 'like', '%' . $request->search . '%');
        }

        $matches = $query->orderBy('match_date', 'desc')->paginate(10);

        return view('admin.matches.index', compact('matches'));
    }

    /**
     * Show the form for creating a new match.
     */
    public function create()
    {
        return view('admin.matches.create');
    }

    /**
     * Store a newly created match.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'opponent' => 'required|string|max:255',
            'match_date' => 'required|date|after:now',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'categories' => 'required|array|min:1',
            'categories.*.name' => 'required|string|max:100',
            'categories.*.price' => 'required|numeric|min:0',
            'categories.*.quota' => 'required|integer|min:1',
        ]);

        $match = FootballMatch::create([
            'opponent' => $validated['opponent'],
            'match_date' => $validated['match_date'],
            'location' => $validated['location'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('matches', 'public');
            $match->update(['banner_image' => $path]);
        }

        // Create ticket categories
        foreach ($validated['categories'] as $category) {
            TicketCategory::create([
                'match_id' => $match->id,
                'name' => $category['name'],
                'price' => $category['price'],
                'quota' => $category['quota'],
            ]);
        }

        return redirect()->route('admin.matches.index')
            ->with('success', 'Pertandingan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing a match.
     */
    public function edit(FootballMatch $match)
    {
        $match->load('ticketCategories');
        return view('admin.matches.edit', compact('match'));
    }

    /**
     * Update the specified match.
     */
    public function update(Request $request, FootballMatch $match)
    {
        $validated = $request->validate([
            'opponent' => 'required|string|max:255',
            'match_date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,live,finished',
            'categories' => 'nullable|array',
            'categories.*.id' => 'nullable|exists:ticket_categories,id',
            'categories.*.name' => 'required|string|max:100',
            'categories.*.price' => 'required|numeric|min:0',
            'categories.*.quota' => 'required|integer|min:1',
        ]);

        $match->update([
            'opponent' => $validated['opponent'],
            'match_date' => $validated['match_date'],
            'location' => $validated['location'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        if ($request->hasFile('banner_image')) {
            // Delete old image
            if ($match->banner_image) {
                Storage::disk('public')->delete($match->banner_image);
            }
            $path = $request->file('banner_image')->store('matches', 'public');
            $match->update(['banner_image' => $path]);
        }

        // Update ticket categories
        if (isset($validated['categories'])) {
            $existingIds = [];

            foreach ($validated['categories'] as $categoryData) {
                if (!empty($categoryData['id'])) {
                    $category = TicketCategory::find($categoryData['id']);
                    if ($category) {
                        $category->update([
                            'name' => $categoryData['name'],
                            'price' => $categoryData['price'],
                            'quota' => $categoryData['quota'],
                        ]);
                        $existingIds[] = $category->id;
                    }
                } else {
                    $newCat = TicketCategory::create([
                        'match_id' => $match->id,
                        'name' => $categoryData['name'],
                        'price' => $categoryData['price'],
                        'quota' => $categoryData['quota'],
                    ]);
                    $existingIds[] = $newCat->id;
                }
            }

            // Remove categories that are no longer in the list (only if no sold tickets)
            $match->ticketCategories()
                ->whereNotIn('id', $existingIds)
                ->where('sold', 0)
                ->delete();
        }

        return redirect()->route('admin.matches.index')
            ->with('success', 'Pertandingan berhasil diperbarui!');
    }

    /**
     * Remove the specified match.
     */
    public function destroy(FootballMatch $match)
    {
        // Prevent deletion if tickets have been sold
        $soldTickets = $match->ticketCategories->sum('sold');
        if ($soldTickets > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus pertandingan karena sudah ada tiket terjual.');
        }

        if ($match->banner_image) {
            Storage::disk('public')->delete($match->banner_image);
        }

        $match->delete();

        return redirect()->route('admin.matches.index')
            ->with('success', 'Pertandingan berhasil dihapus!');
    }
}
