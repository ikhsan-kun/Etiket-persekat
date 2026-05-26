<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\FootballMatch;
use App\Models\TicketCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin Persekat',
            'email' => 'admin@persekat.com',
            'phone' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create sample user
        User::create([
            'name' => 'Suporter Persekat',
            'email' => 'suporter@example.com',
            'phone' => '081234567891',
            'role' => 'user',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create sample matches
        $matches = [
            [
                'opponent' => 'PSMS Medan',
                'match_date' => now()->addDays(7)->setTime(15, 30),
                'location' => 'Stadion Wijaya Kusuma, Tegal',
                'description' => 'Pertandingan seru antara Persekat Tegal melawan PSMS Medan di babak penyisihan Liga 2. Dukung tim kebanggaan kita!',
                'status' => 'published',
            ],
            [
                'opponent' => 'Sriwijaya FC',
                'match_date' => now()->addDays(14)->setTime(19, 00),
                'location' => 'Stadion Wijaya Kusuma, Tegal',
                'description' => 'Big match! Persekat Tegal vs Sriwijaya FC. Pertandingan yang ditunggu-tunggu seluruh suporter.',
                'status' => 'published',
            ],
            [
                'opponent' => 'PSBS Biak',
                'match_date' => now()->addDays(21)->setTime(15, 30),
                'location' => 'Stadion Wijaya Kusuma, Tegal',
                'description' => 'Laga kandang Persekat menghadapi PSBS Biak. Ayo ramaikan stadion!',
                'status' => 'draft',
            ],
            [
                'opponent' => 'Persiraja Banda Aceh',
                'match_date' => now()->subDays(3)->setTime(19, 00),
                'location' => 'Stadion Wijaya Kusuma, Tegal',
                'description' => 'Pertandingan terakhir yang berakhir dengan kemenangan gemilang!',
                'status' => 'finished',
            ],
        ];

        foreach ($matches as $matchData) {
            $match = FootballMatch::create($matchData);

            // Create ticket categories for each match
            TicketCategory::create([
                'match_id' => $match->id,
                'name' => 'VIP',
                'price' => 150000,
                'quota' => 200,
                'sold' => $match->status === 'finished' ? 180 : 0,
            ]);

            TicketCategory::create([
                'match_id' => $match->id,
                'name' => 'Tribun Utara',
                'price' => 75000,
                'quota' => 500,
                'sold' => $match->status === 'finished' ? 420 : 0,
            ]);

            TicketCategory::create([
                'match_id' => $match->id,
                'name' => 'Tribun Selatan',
                'price' => 75000,
                'quota' => 500,
                'sold' => $match->status === 'finished' ? 380 : 0,
            ]);

            TicketCategory::create([
                'match_id' => $match->id,
                'name' => 'Ekonomi',
                'price' => 35000,
                'quota' => 1000,
                'sold' => $match->status === 'finished' ? 850 : 0,
            ]);
        }
    }
}
