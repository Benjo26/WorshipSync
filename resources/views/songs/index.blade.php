@extends('layouts.app')

@section('content')
    <section class="page-head">
        <div>
            <p class="eyebrow">Songs</p>
            <h1>Song Library</h1>
        </div>
        <a class="button" href="{{ route('songs.create') }}">New Song</a>
    </section>

    <section class="song-grid">
        @foreach ($songs as $song)
            <article class="song-card">
                <p class="song-meta">{{ $song->artist ?: 'Unknown Artist' }}</p>
                <h2>{{ $song->title }}</h2>
                <p>{{ $song->default_key }} • {{ $song->bpm }} BPM • {{ implode(' / ', $song->structure) }}</p>
                <div class="inline-actions">
                    <a class="button ghost" href="{{ route('songs.player', $song) }}">Player</a>
                    <a class="button subtle" href="{{ route('songs.edit', $song) }}">Edit</a>
                </div>
            </article>
        @endforeach
    </section>

    <div class="pagination-wrap">
        {{ $songs->links() }}
    </div>
@endsection

