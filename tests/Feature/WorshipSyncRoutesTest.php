<?php

namespace Tests\Feature;

use App\Models\Song;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WorshipSyncRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('google.redirect'));
    }

    public function test_authenticated_user_can_view_song_player(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();

        Storage::disk('local')->put('songs/test-chart.json', json_encode([
            'sections' => [
                ['name' => 'Verse 1', 'lines' => ['[G]Amazing grace']],
            ],
        ]));

        $song = Song::query()->create([
            'user_id' => $user->id,
            'title' => 'Amazing Grace',
            'artist' => 'Traditional',
            'default_key' => 'G',
            'bpm' => 72,
            'time_signature' => '4/4',
            'structure' => ['Verse 1'],
            'chart_path' => 'songs/test-chart.json',
            'notes' => 'Watch the intro dynamics.',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('songs.player', $song));

        $response->assertOk();
        $response->assertSee('Amazing Grace');
        $response->assertSee('Tap Tempo');
    }
}

