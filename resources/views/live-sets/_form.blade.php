@php
    $selectedSongs = collect(old('songs', $selectedSongs->pluck('id')->all()))
        ->map(fn ($songId) => $songs->firstWhere('id', (int) $songId))
        ->filter()
        ->values();
@endphp

@if ($errors->any())
    <div class="error-box">
        <strong>Please fix the highlighted fields.</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="studio-grid">
    <label>
        <span>Live Set Name</span>
        <input name="name" value="{{ old('name', $liveSet?->name ?? '') }}" required placeholder="Sunday First Service">
    </label>

    <div class="live-set-builder" data-live-set-builder>
        <section class="live-set-browser panel">
            <div class="live-set-section-head">
                <div>
                    <p class="eyebrow">Songs</p>
                    <h2>Choose Songs</h2>
                </div>
                <span class="selection-count" data-live-count>{{ $selectedSongs->count() }} selected</span>
            </div>

            <div class="live-song-list">
                @forelse ($songs as $song)
                    <article class="live-song-option" data-song-id="{{ $song->id }}">
                        <div>
                            <p class="song-meta">{{ $song->artist ?: 'Unknown Artist' }}</p>
                            <h3>{{ $song->title }}</h3>
                            <div class="song-tags">
                                <span>{{ $song->default_key }}</span>
                                <span>{{ $song->bpm }} BPM</span>
                                <span>{{ $song->time_signature }}</span>
                            </div>
                        </div>
                        <button
                            class="button ghost"
                            type="button"
                            data-live-add
                            data-song='@json([
                                'id' => $song->id,
                                'title' => $song->title,
                                'artist' => $song->artist ?: 'Unknown Artist',
                                'default_key' => $song->default_key,
                                'bpm' => $song->bpm,
                                'time_signature' => $song->time_signature,
                            ])'
                        >
                            Add
                        </button>
                    </article>
                @empty
                    <article class="empty-card">
                        <p class="eyebrow">No Songs Yet</p>
                        <h2>Add songs first.</h2>
                        <p>Create a few songs before building your first live set.</p>
                        <a class="button" href="{{ route('songs.create') }}">Create Song</a>
                    </article>
                @endforelse
            </div>
        </section>

        <section class="live-set-order panel">
            <div class="live-set-section-head">
                <div>
                    <p class="eyebrow">Order</p>
                    <h2>Set Flow</h2>
                </div>
            </div>

            <ol class="live-set-selection" data-live-selection>
                @foreach ($selectedSongs as $song)
                    <li class="live-set-item" data-song-id="{{ $song->id }}">
                        <input type="hidden" name="songs[]" value="{{ $song->id }}">
                        <span class="live-set-order-pill" data-live-position>{{ $loop->iteration }}</span>
                        <div class="live-set-item-copy">
                            <strong>{{ $song->title }}</strong>
                            <small>{{ $song->artist ?: 'Unknown Artist' }} · {{ $song->default_key }} · {{ $song->bpm }} BPM</small>
                        </div>
                        <div class="live-set-item-actions">
                            <button type="button" class="button subtle" data-live-up>Up</button>
                            <button type="button" class="button subtle" data-live-down>Down</button>
                            <button type="button" class="button danger-outline" data-live-remove>Remove</button>
                        </div>
                    </li>
                @endforeach
            </ol>

            <div class="live-set-empty {{ $selectedSongs->isNotEmpty() ? 'is-hidden' : '' }}" data-live-empty>
                <p class="eyebrow">Start Building</p>
                <h2>No songs selected</h2>
                <p>Add songs from the left, then move them into the exact order you want for the live set.</p>
            </div>
        </section>
    </div>
</div>
