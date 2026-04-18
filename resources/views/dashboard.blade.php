@extends('layouts.app')

@section('content')
    <section class="library-hero hero-premium">
        <div class="library-hero-copy">
            <p class="eyebrow">Dashboard</p>
            <h1>Library</h1>
            <p class="section-lead">
                Your team’s songs, keys, and rehearsal-ready charts in one calm workspace.
            </p>
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <div>
                <p class="eyebrow">Recent</p>
                <h2>Latest Songs</h2>
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
                <p class="eyebrow">Start Here</p>
                <h2>No songs yet</h2>
                <p>Add your first ChordPro chart and build a cleaner song library for your team.</p>
                <a class="button" href="{{ route('songs.create') }}">Create Song</a>
            </article>
        @endforelse
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <div>
                <p class="eyebrow">Live Sets</p>
                <h2>Recent Live Sets</h2>
            </div>
            <a class="button ghost" href="{{ route('live-sets.index') }}">View All</a>
        </div>

        <div class="live-set-grid">
        @forelse ($liveSets as $liveSet)
            <article class="live-set-card">
                <p class="song-meta">Live Set</p>
                <h2>{{ $liveSet->name }}</h2>
                <div class="song-tags">
                    <span>{{ $liveSet->songs_count }} Songs</span>
                    <span>Ordered</span>
                </div>
                <div class="inline-actions">
                    <a class="button ghost" href="{{ route('live-sets.show', $liveSet) }}">Preview</a>
                    <a class="button subtle" href="{{ route('live-sets.edit', $liveSet) }}">Edit</a>
                </div>
            </article>
        @empty
            <article class="empty-card">
                <p class="eyebrow">Set Ready</p>
                <h2>No live sets yet</h2>
                <p>Group your songs into rehearsal or service order and preview the whole flow before you go live.</p>
                <a class="button" href="{{ route('live-sets.create') }}">Create Live Set</a>
            </article>
        @endforelse
        </div>
    </section>
@endsection
