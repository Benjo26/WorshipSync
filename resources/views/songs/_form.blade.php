@php
    $song = $song ?? null;
    $chart = $chart ?? [
        'sections' => [
            ['name' => 'Verse 1', 'lines' => ['[G]Amazing [D]grace']],
            ['name' => 'Chorus', 'lines' => ['[C]Hallelujah']],
        ],
    ];
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

<div class="form-grid">
    <label>
        <span>Song Title</span>
        <input name="title" value="{{ old('title', $song?->title) }}" required>
    </label>

    <label>
        <span>Artist</span>
        <input name="artist" value="{{ old('artist', $song?->artist) }}">
    </label>

    <label>
        <span>Default Key</span>
        <input name="default_key" value="{{ old('default_key', $song?->default_key ?? 'G') }}" required>
    </label>

    <label>
        <span>BPM</span>
        <input type="number" min="40" max="240" name="bpm" value="{{ old('bpm', $song?->bpm ?? 72) }}" required>
    </label>

    <label>
        <span>Time Signature</span>
        <input name="time_signature" value="{{ old('time_signature', $song?->time_signature ?? '4/4') }}" required>
    </label>

    <label>
        <span>Structure</span>
        <input
            name="structure_csv"
            value="{{ old('structure_csv', $song ? implode(', ', $song->structure) : 'Verse 1, Chorus, Verse 2, Chorus, Bridge, Chorus') }}"
            required
        >
        <small>Comma separated section order</small>
    </label>

    <label class="full">
        <span>Notes</span>
        <textarea name="notes" rows="4">{{ old('notes', $song?->notes) }}</textarea>
    </label>

    <label class="full">
        <span>Chord Chart JSON</span>
        <textarea name="chart_json_raw" rows="16" required>{{ old('chart_json_raw', json_encode($chart, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) }}</textarea>
        <small>Use section names and lyric lines with chords like <code>[G]Amazing [D]grace</code>.</small>
    </label>
</div>
