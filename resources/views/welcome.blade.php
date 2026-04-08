@extends('layouts.app')

@section('content')
    <section class="hero">
        <div>
            <p class="eyebrow">Built for rehearsals, teams, and Sunday sets</p>
            <h1>Store chords, arrange songs, and lead with confidence.</h1>
            <p class="lead">
                WorshipSync gives your band one place to keep chord charts, section flow, song keys, BPM, and rehearsal notes.
                Open any song in a distraction-free player with live transpose controls and a built-in metronome.
            </p>
            <div class="hero-actions">
                <a class="button" href="{{ route('google.redirect') }}">Continue with Google</a>
            </div>
        </div>

        <div class="feature-card">
            <h2>What musicians get</h2>
            <ul>
                <li>Quick song entry with section-based arrangements</li>
                <li>Chord charts saved in Laravel storage</li>
                <li>Instant transpose for different vocal keys</li>
                <li>Tap tempo metronome for rehearsal practice</li>
                <li>Google sign-in for easy team access</li>
            </ul>
        </div>
    </section>
@endsection
