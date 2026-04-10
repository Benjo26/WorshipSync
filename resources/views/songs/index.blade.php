@extends('layouts.app')

@section('content')
    <section class="library-hero hero-premium">
        <div class="library-hero-copy">
            <p class="eyebrow">Songs</p>
            <h1>Songs</h1>
            <p class="section-lead">
                Browse, edit, and open every chart from one refined rehearsal view.
            </p>
        </div>
        <div class="library-hero-actions">
            <a class="button" href="{{ route('songs.create') }}">New Song</a>
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <div>
                <p class="eyebrow">Library</p>
                <h2>Your Songs</h2>
            </div>
            <form method="GET" action="{{ route('songs.index') }}" class="search-form">
                <label class="search-input">
                    <span class="sr-only">Search songs</span>
                    <input
                        type="search"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Search title, artist, or key"
                    >
                </label>
                @if (! empty($search))
                    <a class="button subtle" href="{{ route('songs.index') }}">Clear</a>
                @endif
            </form>
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
                    <a class="button ghost" href="{{ route('songs.player', $song) }}">Player</a>
                    <a class="button subtle" href="{{ route('songs.edit', $song) }}">Edit</a>
                    <form method="POST" action="{{ route('songs.destroy', $song) }}" onsubmit="return confirm('Delete {{ addslashes($song->title) }}? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button class="button danger-outline" type="submit">Delete</button>
                    </form>
                </div>
            </article>
        @empty
            <article class="empty-card">
                @if (! empty($search))
                    <p class="eyebrow">No Match</p>
                    <h2>No songs found.</h2>
                    <p>Try another title, artist, or key, or clear the search to see your full library.</p>
                    <a class="button subtle" href="{{ route('songs.index') }}">Clear Search</a>
                @else
                    <p class="eyebrow">Ready To Start</p>
                    <h2>Your library is ready.</h2>
                    <p>Start with one ChordPro song and shape a clean archive for your worship team.</p>
                    <a class="button" href="{{ route('songs.create') }}">Create Song</a>
                @endif
            </article>
        @endforelse
        </div>
    </section>

    <div class="pagination-wrap">
        {{ $songs->links() }}
    </div>
@endsection
