@extends('layouts.app')

@section('content')
    <section class="library-hero hero-premium">
        <div class="library-hero-copy">
            <p class="eyebrow">Live Preview</p>
            <h1>{{ $liveSet->name }}</h1>
            <p class="section-lead">Every song below is shown in exact set order so your team can rehearse the service flow as one sequence.</p>
        </div>
        <div class="library-hero-actions">
            <a class="button ghost" href="{{ route('live-sets.edit', $liveSet) }}">Edit Set</a>
        </div>
    </section>

    <section class="section-block live-set-preview">
        <div class="section-head">
            <div>
                <p class="eyebrow">Preview</p>
                <h2>{{ $liveSet->songs->count() }} Songs In Order</h2>
            </div>
        </div>

        <div class="live-set-preview-list">
            @foreach ($previewSongs as $previewSong)
                <article class="live-set-preview-card">
                    <div class="live-set-preview-head">
                        <span class="live-set-order-pill">{{ str_pad((string) $previewSong['order'], 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="live-set-preview-copy">
                            <p class="song-meta">{{ $previewSong['song']->artist ?: 'Unknown Artist' }}</p>
                            <h2>{{ $previewSong['song']->title }}</h2>
                            <div class="song-tags">
                                <span>{{ $previewSong['song']->default_key }}</span>
                                <span>{{ $previewSong['song']->bpm }} BPM</span>
                                <span>{{ $previewSong['song']->time_signature }}</span>
                            </div>
                        </div>
                        <a class="button ghost" href="{{ route('songs.player', $previewSong['song']) }}">Open Player</a>
                    </div>

                    <div class="chart-panel">
                        @forelse ($previewSong['chart']['sections'] as $section)
                            <article class="chart-section">
                                <h2>{{ $section['name'] }}</h2>
                                <div class="chart-lines">
                                    @foreach ($section['lines'] as $line)
                                        <div>{{ $line }}</div>
                                    @endforeach
                                </div>
                            </article>
                        @empty
                            <article class="chart-section chart-empty-state">
                                <p>No chart lines were found for this song yet.</p>
                            </article>
                        @endforelse
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
