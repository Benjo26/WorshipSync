@extends('layouts.app')

@section('content')
    <section class="landing-hero">
        <div class="landing-copy">
            <p class="landing-kicker">Worship planning, rebuilt</p>
            <h1>Lead every set with a cleaner, faster worship workspace.</h1>
            <p class="landing-lead">
                Keep ChordPro charts, transpose live, lock tempo, and open every song in a player built for rehearsals and Sunday mornings.
            </p>
            <div class="landing-actions">
                <a class="button landing-primary" href="{{ route('google.redirect') }}">Start With Google</a>
                <a class="button landing-secondary" href="{{ route('songs.index') }}">Open Library</a>
            </div>
            <div class="landing-stats">
                <span>ChordPro native</span>
                <span>Live transpose</span>
                <span>Metronome ready</span>
            </div>
        </div>

        <div class="landing-visual" aria-hidden="true">
            <div class="landing-orb"></div>
            <div class="landing-device">
                <div class="landing-device-top">
                    <span class="landing-dot"></span>
                    <span class="landing-dot"></span>
                    <span class="landing-dot"></span>
                </div>
                <div class="landing-device-body">
                    <div class="landing-panel landing-panel-primary">
                        <p class="eyebrow">Now Playing</p>
                        <strong>Amazing Grace</strong>
                        <div class="landing-chip-row">
                            <span>G Major</span>
                            <span>72 BPM</span>
                            <span>4/4</span>
                        </div>
                    </div>
                    <div class="landing-panel landing-panel-secondary">
                        <div class="landing-mini-meter">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="landing-mini-copy">
                            <p>Transpose and rehearse in one player.</p>
                        </div>
                    </div>
                    <div class="landing-floating-card landing-floating-left">
                        <small>Set Ready</small>
                        <strong>Sunday 8:30 AM</strong>
                    </div>
                    <div class="landing-floating-card landing-floating-right">
                        <small>Live Tools</small>
                        <strong>Tap Tempo</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
