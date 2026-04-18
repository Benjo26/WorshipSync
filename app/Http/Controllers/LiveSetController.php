<?php

namespace App\Http\Controllers;

use App\Http\Requests\LiveSetRequest;
use App\Models\LiveSet;
use App\Models\Song;
use App\Services\ChordProParser;
use App\Services\SongChartStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LiveSetController extends Controller
{
    public function __construct(
        private readonly ChordProParser $chordProParser,
        private readonly SongChartStorage $chartStorage
    ) {
    }

    public function index(): View
    {
        $liveSets = Auth::user()
            ->liveSets()
            ->withCount('songs')
            ->latest()
            ->paginate(12);

        return view('live-sets.index', [
            'liveSets' => $liveSets,
        ]);
    }

    public function create(): View
    {
        return view('live-sets.create', [
            'songs' => $this->availableSongs(),
            'selectedSongs' => collect(),
        ]);
    }

    public function store(LiveSetRequest $request): RedirectResponse
    {
        $payload = $request->validated();

        $this->ensureOwnedSongs($payload['songs']);

        $liveSet = Auth::user()->liveSets()->create([
            'name' => $payload['name'],
        ]);

        $this->syncSongs($liveSet, $payload['songs']);

        return redirect()
            ->route('live-sets.show', $liveSet)
            ->with('status', 'Live set created successfully.');
    }

    public function show(LiveSet $liveSet): View
    {
        $this->authorizeLiveSet($liveSet);

        $liveSet->load('songs');

        return view('live-sets.show', [
            'liveSet' => $liveSet,
            'previewSongs' => $liveSet->songs->map(fn (Song $song, int $index) => [
                'order' => $index + 1,
                'song' => $song,
                'chart' => $this->chordProParser->parse($this->readChordPro($song)),
            ]),
        ]);
    }

    public function edit(LiveSet $liveSet): View
    {
        $this->authorizeLiveSet($liveSet);

        $liveSet->load('songs');

        return view('live-sets.edit', [
            'liveSet' => $liveSet,
            'songs' => $this->availableSongs(),
            'selectedSongs' => $liveSet->songs,
        ]);
    }

    public function update(LiveSetRequest $request, LiveSet $liveSet): RedirectResponse
    {
        $this->authorizeLiveSet($liveSet);

        $payload = $request->validated();

        $this->ensureOwnedSongs($payload['songs']);

        $liveSet->update([
            'name' => $payload['name'],
        ]);

        $this->syncSongs($liveSet, $payload['songs']);

        return redirect()
            ->route('live-sets.show', $liveSet)
            ->with('status', 'Live set updated successfully.');
    }

    public function destroy(LiveSet $liveSet): RedirectResponse
    {
        $this->authorizeLiveSet($liveSet);

        $liveSet->delete();

        return redirect()
            ->route('live-sets.index')
            ->with('status', 'Live set deleted.');
    }

    private function authorizeLiveSet(LiveSet $liveSet): void
    {
        abort_unless($liveSet->user_id === Auth::id(), 403);
    }

    private function availableSongs(): Collection
    {
        return Auth::user()
            ->songs()
            ->orderBy('title')
            ->get();
    }

    private function ensureOwnedSongs(array $songIds): void
    {
        $ownedCount = Auth::user()
            ->songs()
            ->whereIn('id', $songIds)
            ->count();

        abort_unless($ownedCount === count($songIds), 403);
    }

    private function syncSongs(LiveSet $liveSet, array $songIds): void
    {
        $liveSet->songs()->sync(
            collect($songIds)
                ->values()
                ->mapWithKeys(fn (int $songId, int $index) => [
                    $songId => ['position' => $index + 1],
                ])
                ->all()
        );
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
