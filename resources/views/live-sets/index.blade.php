@extends('layouts.app')

@section('content')
    <section class="library-hero hero-premium">
        <div class="library-hero-copy">
            <p class="eyebrow">Live Sets</p>
            <h1>Live Sets</h1>
            <p class="section-lead">Build service-ready song orders and preview the full flow before you go live.</p>
        </div>
        <div class="library-hero-actions">
            <a class="button" href="{{ route('live-sets.create') }}">New Live Set</a>
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <div>
                <p class="eyebrow">Saved Sets</p>
                <h2>Your Live Sets</h2>
            </div>
        </div>

        <div class="live-set-grid">
            @forelse ($liveSets as $liveSet)
                <article class="live-set-card">
                    <p class="song-meta">Live Set</p>
                    <h2>{{ $liveSet->name }}</h2>
                    <div class="song-tags">
                        <span>{{ $liveSet->songs_count }} Songs</span>
                        <span>Preview Ready</span>
                    </div>
                    <div class="inline-actions">
                        <a class="button ghost" href="{{ route('live-sets.show', $liveSet) }}">Preview</a>
                        <a class="button subtle" href="{{ route('live-sets.edit', $liveSet) }}">Edit</a>
                        <form method="POST" action="{{ route('live-sets.destroy', $liveSet) }}" onsubmit="return confirm('Delete {{ addslashes($liveSet->name) }}? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button class="button danger-outline" type="submit">Delete</button>
                        </form>
                    </div>
                </article>
            @empty
                <article class="empty-card">
                    <p class="eyebrow">Live Ready</p>
                    <h2>No live sets yet.</h2>
                    <p>Create your first live set, choose songs, and arrange them in service order.</p>
                    <a class="button" href="{{ route('live-sets.create') }}">Create Live Set</a>
                </article>
            @endforelse
        </div>
    </section>

    <div class="pagination-wrap">
        {{ $liveSets->links() }}
    </div>
@endsection
