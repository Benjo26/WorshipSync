@extends('layouts.app')

@section('content')
    <section class="page-head">
        <div>
            <p class="eyebrow">Dashboard</p>
            <h1>Your worship library</h1>
        </div>
        <a class="button" href="{{ route('songs.create') }}">Add Song</a>
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

    <section class="song-grid">
        @forelse ($songs as $song)
            <article class="song-card">
                <p class="song-meta">{{ $song->artist ?: 'Unknown Artist' }}</p>
                <h2>{{ $song->title }}</h2>
                <p>{{ $song->default_key }} • {{ $song->bpm }} BPM • {{ $song->time_signature }}</p>
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
                <h2>No songs yet</h2>
                <p>Create your first chart and start building your worship library.</p>
                <a class="button" href="{{ route('songs.create') }}">Create Song</a>
            </article>
        @endforelse
    </section>
@endsection
