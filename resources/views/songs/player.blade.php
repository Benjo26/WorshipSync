@extends('layouts.app')

@section('content')
    <section
        class="player-shell"
        data-song-player
        data-default-key="{{ $song->default_key }}"
        data-bpm="{{ $song->bpm }}"
        data-beats-per-bar="{{ (int) explode('/', $song->time_signature)[0] }}"
        data-chart='@json($chart)'
        data-chordpro='@json($chordPro)'
    >
        <div class="player-head">
            <div>
                <p class="eyebrow">{{ $song->artist ?: 'Unknown Artist' }}</p>
                <h1>{{ $song->title }}</h1>
                <p>{{ $song->default_key }} • {{ $song->bpm }} BPM • {{ $song->time_signature }}</p>
            </div>
            <div class="inline-actions">
                <a class="button ghost" href="{{ route('songs.edit', $song) }}">Edit</a>
                <a class="button subtle" href="{{ route('songs.index') }}">Back</a>
                <form method="POST" action="{{ route('songs.destroy', $song) }}" onsubmit="return confirm('Delete {{ addslashes($song->title) }}? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button class="button danger-outline" type="submit">Delete</button>
                </form>
            </div>
        </div>

        <section class="player-toolbar">
            <div class="toolbar-group control-card">
                <span class="control-label">Transpose</span>
                <div class="control-cluster">
                    <button type="button" class="button subtle control-button" data-transpose-down>-</button>
                    <strong class="control-value" data-current-key>{{ $song->default_key }}</strong>
                    <button type="button" class="button subtle control-button" data-transpose-up>+</button>
                </div>
            </div>

            <div class="toolbar-group control-card">
                <span class="control-label">Metronome</span>
                <div class="control-cluster">
                    <button type="button" class="button subtle" data-metronome-toggle>Start</button>
                    <button type="button" class="button ghost" data-tap-tempo>Tap Tempo</button>
                    <strong class="tempo-readout"><span data-bpm-display>{{ $song->bpm }}</span> BPM</strong>
                </div>
            </div>

            <div class="toolbar-group control-card beat-indicator">
                <span class="control-label">Beat</span>
                <span data-beat-indicator>1</span>
            </div>
        </section>

        <section class="chart-panel" data-chart-output></section>
    </section>

    @if (! file_exists(public_path('build/manifest.json')) && ! file_exists(public_path('hot')))
        <script>
            const NOTES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
            const FLAT_TO_SHARP = { Db: 'C#', Eb: 'D#', Gb: 'F#', Ab: 'G#', Bb: 'A#' };

            const normalizeNote = (note) => FLAT_TO_SHARP[note] || note;

            const transposeChord = (chord, steps) =>
                chord.replace(/[A-G](#|b)?/g, (match) => {
                    const normalized = normalizeNote(match);
                    const index = NOTES.indexOf(normalized);
                    return index === -1 ? match : NOTES[(index + steps + NOTES.length) % NOTES.length];
                });

            const BAR_CHORD_PATTERN = /^\|?\s*[A-G][^|]*\|/;

            const renderBarLine = (line, steps) => {
                const segments = line.split('|');

                if (segments.length < 3) {
                    return null;
                }

                const measures = segments
                    .map((segment) => segment.trim())
                    .filter((segment) => segment.length > 0)
                    .map((segment) => `<span class="measure">${transposeChord(segment, steps)}</span>`);

                if (measures.length === 0) {
                    return null;
                }

                return `<span class="measure-line">${measures.join('')}</span>`;
            };

            const renderLine = (line, steps) => {
                if (BAR_CHORD_PATTERN.test(line.trim())) {
                    const renderedBarLine = renderBarLine(line, steps);

                    if (renderedBarLine) {
                        return renderedBarLine;
                    }
                }

                return line.replace(/\[([^\]]+)\]/g, (_, chord) => `<span class="chord">[${transposeChord(chord, steps)}]</span>`);
            };

            const bootSongPlayer = (root) => {
                const chart = JSON.parse(root.dataset.chart || '{"sections": []}');
                const defaultKey = root.dataset.defaultKey;
                let bpm = Number(root.dataset.bpm || 72);
                const beatsPerBar = Number(root.dataset.beatsPerBar || 4);
                let transpose = 0;
                let metronomeTimer = null;
                let beat = 0;
                const tapHistory = [];
                let audioContext = null;

                const chartOutput = root.querySelector('[data-chart-output]');
                const currentKey = root.querySelector('[data-current-key]');
                const bpmDisplay = root.querySelector('[data-bpm-display]');
                const beatIndicator = root.querySelector('[data-beat-indicator]');
                const toggle = root.querySelector('[data-metronome-toggle]');

                const render = () => {
                    if (!chart.sections.length) {
                        chartOutput.innerHTML = '<article class="chart-section chart-empty-state"><p>No chart lines were found for this song yet.</p></article>';
                        currentKey.textContent = transposeChord(defaultKey, transpose);
                        bpmDisplay.textContent = bpm;
                        return;
                    }

                    chartOutput.innerHTML = chart.sections.map((section) => `
                        <article class="chart-section">
                            <h2>${section.name}</h2>
                            <div class="chart-lines">
                                ${section.lines.map((line) => `<div>${renderLine(line, transpose)}</div>`).join('')}
                            </div>
                        </article>
                    `).join('');

                    currentKey.textContent = transposeChord(defaultKey, transpose);
                    bpmDisplay.textContent = bpm;
                };

                const stopMetronome = () => {
                    if (metronomeTimer) {
                        clearInterval(metronomeTimer);
                        metronomeTimer = null;
                    }

                    toggle.textContent = 'Start';
                    beat = 0;
                    beatIndicator.textContent = '1';
                };

                const tick = () => {
                    beat = (beat % beatsPerBar) + 1;
                    beatIndicator.textContent = String(beat);

                    if (!audioContext) {
                        return;
                    }

                    const oscillator = audioContext.createOscillator();
                    const gain = audioContext.createGain();
                    oscillator.frequency.value = beat === 1 ? 920 : 680;
                    gain.gain.value = 0.12;
                    oscillator.connect(gain);
                    gain.connect(audioContext.destination);
                    oscillator.start();
                    oscillator.stop(audioContext.currentTime + 0.05);
                };

                const startMetronome = async () => {
                    if (!audioContext && (window.AudioContext || window.webkitAudioContext)) {
                        audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    }

                    if (!audioContext) {
                        toggle.textContent = 'Audio unavailable';
                        return;
                    }

                    if (audioContext.state === 'suspended') {
                        await audioContext.resume();
                    }

                    stopMetronome();
                    tick();
                    metronomeTimer = window.setInterval(tick, (60 / bpm) * 1000);
                    toggle.textContent = 'Stop';
                };

                root.querySelector('[data-transpose-down]')?.addEventListener('click', () => {
                    transpose -= 1;
                    render();
                });

                root.querySelector('[data-transpose-up]')?.addEventListener('click', () => {
                    transpose += 1;
                    render();
                });

                toggle?.addEventListener('click', async () => {
                    if (metronomeTimer) {
                        stopMetronome();
                        return;
                    }

                    await startMetronome();
                });

                root.querySelector('[data-tap-tempo]')?.addEventListener('click', () => {
                    const now = Date.now();
                    tapHistory.push(now);

                    if (tapHistory.length > 5) {
                        tapHistory.shift();
                    }

                    if (tapHistory.length >= 2) {
                        const intervals = tapHistory.slice(1).map((time, index) => time - tapHistory[index]);
                        const average = intervals.reduce((sum, value) => sum + value, 0) / intervals.length;
                        bpm = Math.max(40, Math.min(240, Math.round(60000 / average)));
                        render();

                        if (metronomeTimer) {
                            void startMetronome();
                        }
                    }
                });

                render();
            };

            document.querySelectorAll('[data-song-player]').forEach(bootSongPlayer);
        </script>
    @endif
@endsection
