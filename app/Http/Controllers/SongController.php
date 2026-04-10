<?php

namespace App\Http\Controllers;

use App\Http\Requests\SongRequest;
use App\Models\Song;
use App\Services\ChordProParser;
use App\Services\SongChartStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SongController extends Controller
{
    public function __construct(
        private readonly SongChartStorage $chartStorage,
        private readonly ChordProParser $chordProParser
    ) {
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $songs = Auth::user()
            ->songs()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($songQuery) use ($search) {
                    $songQuery
                        ->where('title', 'like', '%' . $search . '%')
                        ->orWhere('artist', 'like', '%' . $search . '%')
                        ->orWhere('default_key', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('songs.index', [
            'songs' => $songs,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('songs.create');
    }

    public function store(SongRequest $request): RedirectResponse
    {
        $payload = $request->validated();

        $song = Auth::user()->songs()->create([
            'title' => $payload['title'],
            'artist' => $payload['artist'] ?? null,
            'default_key' => $payload['default_key'],
            'bpm' => $payload['bpm'],
            'time_signature' => $payload['time_signature'],
            'structure' => [],
            'notes' => null,
            'chordpro' => $payload['chordpro'],
            'chart_path' => '',
        ]);

        return redirect()
            ->route('songs.player', $song)
            ->with('status', 'Song created successfully.');
    }

    public function edit(Song $song): View
    {
        $this->authorizeSong($song);

        return view('songs.edit', [
            'song' => $song,
            'chordPro' => $this->readChordPro($song),
        ]);
    }

    public function update(SongRequest $request, Song $song): RedirectResponse
    {
        $this->authorizeSong($song);

        $payload = $request->validated();

        if ($song->chart_path) {
            $this->chartStorage->delete($song->chart_path);
        }

        $song->update([
            'title' => $payload['title'],
            'artist' => $payload['artist'] ?? null,
            'default_key' => $payload['default_key'],
            'bpm' => $payload['bpm'],
            'time_signature' => $payload['time_signature'],
            'structure' => [],
            'notes' => null,
            'chordpro' => $payload['chordpro'],
            'chart_path' => '',
        ]);

        return redirect()
            ->route('songs.player', $song)
            ->with('status', 'Song updated successfully.');
    }

    public function destroy(Song $song): RedirectResponse
    {
        $this->authorizeSong($song);

        if ($song->chart_path) {
            $this->chartStorage->delete($song->chart_path);
        }

        $song->delete();

        return redirect()
            ->route('songs.index')
            ->with('status', 'Song deleted.');
    }

    public function player(Song $song): View
    {
        $this->authorizeSong($song);

        return view('songs.player', [
            'song' => $song,
            'chart' => $this->chordProParser->parse($this->readChordPro($song)),
            'chordPro' => $this->readChordPro($song),
        ]);
    }

    private function authorizeSong(Song $song): void
    {
        abort_unless($song->user_id === Auth::id(), 403);
    }

    private function readChordPro(Song $song): string
    {
        if (filled($song->chordpro)) {
            return $song->chordpro;
        }

        if (blank($song->chart_path)) {
            return '';
        }

        return $this->chartStorage->read($song->chart_path);
    }
}
