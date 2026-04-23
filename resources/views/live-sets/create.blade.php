@extends('layouts.app')

@section('content')
    <section class="library-hero hero-premium live-set-create-hero">
        <div class="library-hero-copy">
            <p class="eyebrow">Live Set</p>
            <h1>New Live Set</h1>
            <p class="section-lead">Choose multiple songs, lock their order, and preview the full service flow in one place.</p>
        </div>
        <div class="library-hero-actions">
            <a class="button ghost" href="{{ route('live-sets.index') }}">Back to Live Sets</a>
        </div>
    </section>

    <form method="POST" action="{{ route('live-sets.store') }}" class="studio-shell studio-shell-single live-set-form-shell">
        @csrf
        <section class="panel studio-main">
            @include('live-sets._form', ['liveSet' => null])
            <div class="studio-actions">
                <button class="button" type="submit">Save Live Set</button>
            </div>
        </section>
    </form>

    @if (! file_exists(public_path('build/manifest.json')) && ! file_exists(public_path('hot')))
        @include('live-sets._builder-script')
    @endif
@endsection
