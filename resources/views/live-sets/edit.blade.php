@extends('layouts.app')

@section('content')
    <section class="library-hero hero-premium">
        <div class="library-hero-copy">
            <p class="eyebrow">Live Set</p>
            <h1>{{ $liveSet->name }}</h1>
            <p class="section-lead">Adjust the song order, swap songs in or out, and keep your set ready for rehearsal.</p>
        </div>
        <div class="library-hero-actions">
            <a class="button ghost" href="{{ route('live-sets.show', $liveSet) }}">Back to Preview</a>
        </div>
    </section>

    <form method="POST" action="{{ route('live-sets.update', $liveSet) }}" class="studio-shell studio-shell-single">
        @csrf
        @method('PUT')
        <section class="panel studio-main">
            @include('live-sets._form')
            <div class="studio-actions">
                <button class="button" type="submit">Update Live Set</button>
            </div>
        </section>
    </form>

    @if (! file_exists(public_path('build/manifest.json')) && ! file_exists(public_path('hot')))
        @include('live-sets._builder-script')
    @endif
@endsection
