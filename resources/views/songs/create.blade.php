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

    <form method="POST" action="{{ route('songs.store') }}" class="studio-shell studio-shell-single">
        @csrf
        <section class="panel studio-main">
            @include('songs._form')
            <div class="studio-actions">
                <button class="button" type="submit">Save Song</button>
            </div>
        </section>
    </form>
@endsection
