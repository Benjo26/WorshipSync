@extends('layouts.app')

@section('content')
    <section
        class="player-shell"
        data-song-player
        data-default-key="{{ $song->default_key }}"
        data-bpm="{{ $song->bpm }}"
        data-beats-per-bar="{{ (int) explode('/', $song->time_signature)[0] }}"
        data-chart='@json($chart)'
    >
        <div class="player-head">
            <div>
                <p class="eyebrow">{{ $song->artist ?: 'Unknown Artist' }}</p>
                <h1>{{ $song->title }}</h1>
                <p>{{ $song->default_key }} • {{ $song->bpm }} BPM • {{ $song->time_signature }}</p>
            </div>
            <div class="inline-actions">
                <a class="button ghost" href="{{ route('songs.edit', $song) }}">Edit</a>
                <a class="button subtle" href="{{ route('songs.index') }}">Back</a>
            </div>
        </div>

        <section class="player-toolbar">
            <div class="toolbar-group">
                <span>Transpose</span>
                <button type="button" class="button subtle" data-transpose-down>-</button>
                <strong data-current-key>{{ $song->default_key }}</strong>
                <button type="button" class="button subtle" data-transpose-up>+</button>
            </div>

            <div class="toolbar-group">
                <span>Metronome</span>
                <button type="button" class="button subtle" data-metronome-toggle>Start</button>
                <button type="button" class="button ghost" data-tap-tempo>Tap Tempo</button>
                <strong><span data-bpm-display>{{ $song->bpm }}</span> BPM</strong>
            </div>

            <div class="toolbar-group beat-indicator">
                <span data-beat-indicator>1</span>
            </div>
        </section>

        <section class="structure-strip">
            @foreach ($song->structure as $part)
                <span>{{ $part }}</span>
            @endforeach
        </section>

        <section class="chart-panel" data-chart-output></section>

        @if ($song->notes)
            <section class="notes-panel">
                <h2>Team Notes</h2>
                <p>{{ $song->notes }}</p>
            </section>
        @endif
    </section>
@endsection
