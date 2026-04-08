@extends('layouts.app')

@section('content')
    <section class="library-hero">
        <div class="library-hero-copy">
            <p class="eyebrow">Dashboard</p>
            <h1>Your worship library</h1>
            <p class="section-lead">
                Keep your chord charts, keys, and rehearsal flow in one polished space built for fast prep and calm Sunday mornings.
            </p>
        </div>
        <div class="library-hero-actions">
            <a class="button" href="{{ route('songs.create') }}">Add Song</a>
        </div>
    </section>

    <section class="stats-grid">
        <article class="stat-card">
            <span>Total Songs</span>
            <strong>{{ $songCount }}</strong>
        </article>
        <article class="stat-card">
            <span>Latest BPM Range</span>
            <strong>{{ $averageBpm ? round($averageBpm) . ' avg' : 'No songs yet' }}</strong>
        </article>
        <article class="stat-card">
            <span>Default Flow</span>
            <strong>Verse / Chorus / Bridge</strong>
        </article>
    </section>

    <section class="section-block">
        <div class="section-head">
            <div>
                <p class="eyebrow">Recent Charts</p>
                <h2>Library Snapshot</h2>
            </div>
        </div>

        <div class="song-grid">
        @forelse ($songs as $song)
            <article class="song-card">
                <p class="song-meta">{{ $song->artist ?: 'Unknown Artist' }}</p>
                <h2>{{ $song->title }}</h2>
                <div class="song-tags">
                    <span>{{ $song->default_key }}</span>
                    <span>{{ $song->bpm }} BPM</span>
                    <span>{{ $song->time_signature }}</span>
                </div>
                <div class="inline-actions">
                    <a class="button ghost" href="{{ route('songs.player', $song) }}">Open Player</a>
                    <form method="POST" action="{{ route('songs.destroy', $song) }}" onsubmit="return confirm('Delete {{ addslashes($song->title) }}? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button class="button danger-outline" type="submit">Delete</button>
                    </form>
                </div>
            </article>
        @empty
            <article class="empty-card">
                <p class="eyebrow">Fresh Start</p>
                <h2>No songs yet</h2>
                <p>Create your first ChordPro chart and start building a set-ready library for your team.</p>
                <a class="button" href="{{ route('songs.create') }}">Create Song</a>
            </article>
        @endforelse
        </div>
    </section>
@endsection
