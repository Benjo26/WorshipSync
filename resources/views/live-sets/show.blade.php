@extends('layouts.app')

@section('content')
    @php
        $barChordPattern = '/^\|?\s*[A-G][^|]*\|/';

        $renderLine = function (string $line) use ($barChordPattern): string {
            if (preg_match($barChordPattern, trim($line))) {
                $measures = collect(explode('|', $line))
                    ->map(fn (string $segment) => trim($segment))
                    ->filter()
                    ->map(fn (string $segment) => '<span class="measure">' . e($segment) . '</span>')
                    ->implode('');

                if ($measures !== '') {
                    return '<span class="measure-line">' . $measures . '</span>';
                }
            }

            $escaped = e($line);

            return preg_replace_callback(
                '/\[([^\]]+)\]/',
                fn (array $matches) => '<span class="chord">[' . e($matches[1]) . ']</span>',
                $escaped
            ) ?? $escaped;
        };
    @endphp

    <section class="library-hero hero-premium">
        <div class="library-hero-copy">
            <p class="eyebrow">Live Preview</p>
            <h1>{{ $liveSet->name }}</h1>
            <p class="section-lead">Every song below is shown in exact set order so your team can rehearse the service flow as one sequence.</p>
        </div>
        <div class="library-hero-actions">
            <a class="button" href="{{ route('live-sets.pdf', $liveSet) }}" target="_blank" rel="noopener">Download PDF</a>
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

        <div class="live-set-preview-list live-set-player-list">
            @foreach ($previewSongs as $previewSong)
                <article class="player-shell live-set-player-shell">
                    <div class="player-head">
                        <div>
                            <p class="eyebrow">{{ $previewSong['song']->artist ?: 'Unknown Artist' }}</p>
                            <h1>{{ $previewSong['song']->title }}</h1>
                            <div class="player-meta">
                                <span class="live-set-order-chip">{{ str_pad((string) $previewSong['order'], 2, '0', STR_PAD_LEFT) }}</span>
                                <span>{{ $previewSong['song']->default_key }}</span>
                                <span>{{ $previewSong['song']->bpm }} BPM</span>
                                <span>{{ $previewSong['song']->time_signature }}</span>
                            </div>
                        </div>
                        <div class="inline-actions">
                            <a class="button ghost" href="{{ route('songs.player', $previewSong['song']) }}">Open Player</a>
                        </div>
                    </div>

                    <section class="player-toolbar live-set-toolbar-preview" aria-hidden="true">
                        <div class="toolbar-group control-card">
                            <span class="control-label">Transpose</span>
                            <div class="control-cluster">
                                <span class="button subtle control-button is-static">-</span>
                                <strong class="control-value">{{ $previewSong['song']->default_key }}</strong>
                                <span class="button subtle control-button is-static">+</span>
                            </div>
                        </div>

                        <div class="toolbar-group control-card">
                            <span class="control-label">Metronome</span>
                            <div class="control-cluster">
                                <span class="button subtle is-static">Start</span>
                                <span class="button ghost is-static">Tap Tempo</span>
                                <strong class="tempo-readout">{{ $previewSong['song']->bpm }} BPM</strong>
                                <span class="beat-pill">
                                    <small>Beat</small>
                                    <span>1</span>
                                </span>
                            </div>
                        </div>
                    </section>

                    <div class="chart-panel">
                        @forelse ($previewSong['chart']['sections'] as $section)
                            <article class="chart-section">
                                <h2>{{ $section['name'] }}</h2>
                                <div class="chart-lines">
                                    @foreach ($section['lines'] as $line)
                                        <div>{!! $renderLine($line) !!}</div>
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
