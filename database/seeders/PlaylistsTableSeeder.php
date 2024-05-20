<?php

namespace Database\Seeders;

use App\Models\Playlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlaylistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $playlists = [
            'لیست پخش 1',
            'لیست پخش 2',
            'لیست پخش 3',
            'لیست پخش 4',
            'لیست پخش 5',
        ];

        $userIds = collect([13, 8, 10]);

        foreach ($playlists as $playlist) {
            Playlist::create([
                'user_id' => $userIds->random(),
                'title' => $playlist
            ]);
        }
    }
}
