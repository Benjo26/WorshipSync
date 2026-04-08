@extends('layouts.app')

@section('content')
    <section class="library-hero">
        <div class="library-hero-copy">
            <p class="eyebrow">New Song</p>
            <h1>Create Worship Song</h1>
            <p class="section-lead">
                Drop in your ChordPro chart and shape a clean, player-ready song sheet in one pass.
            </p>
        </div>
        <div class="library-hero-actions">
            <a class="button ghost" href="{{ route('songs.index') }}">Back to Library</a>
        </div>
    </section>

    <form method="POST" action="{{ route('songs.store') }}" class="studio-shell">
        @csrf
        <section class="panel studio-main">
            @include('songs._form')
            <div class="studio-actions">
                <button class="button" type="submit">Save Song</button>
            </div>
        </section>

        <aside class="panel studio-side">
            <p class="eyebrow">Workflow</p>
            <h2>Write once, lead anywhere.</h2>
            <p>
                Paste ChordPro, let the editor pull metadata automatically, then open the player to transpose and rehearse in seconds.
            </p>
            <div class="side-note">
                <span>Pro tip</span>
                <p>Use tags like <code>{title: }</code>, <code>{artist: }</code>, and <code>{comment: Verse 1}</code> to keep charts clean and searchable.</p>
            </div>
            <div class="side-note">
                <span>Live Feel</span>
                <p>Keep your chart lyrical and readable. Short sections, clear tags, and clean chord spacing will feel better in the player.</p>
            </div>
        </aside>
    </form>
@endsection
