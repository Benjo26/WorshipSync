const NOTES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
const FLAT_TO_SHARP = {
    Db: 'C#',
    Eb: 'D#',
    Gb: 'F#',
    Ab: 'G#',
    Bb: 'A#',
};

const normalizeNote = (note) => FLAT_TO_SHARP[note] || note;

const transposeChord = (chord, steps) =>
    chord.replace(/[A-G](#|b)?/g, (match) => {
        const normalized = normalizeNote(match);
        const index = NOTES.indexOf(normalized);

        if (index === -1) {
            return match;
        }

        return NOTES[(index + steps + NOTES.length) % NOTES.length];
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

        chartOutput.innerHTML = chart.sections
            .map(
                (section) => `
                <article class="chart-section">
                    <h2>${section.name}</h2>
                    <div class="chart-lines">
                        ${section.lines.map((line) => `<div>${renderLine(line, transpose)}</div>`).join('')}
                    </div>
                </article>
            `,
            )
            .join('');

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

const extractChordProTag = (text, tag) => {
    const match = text.match(new RegExp(`\\{${tag}\\s*:\\s*([^}]+)\\}`, 'i'));

    return match ? match[1].trim() : '';
};

const bootChordProForm = (root) => {
    const chordProInput = root.querySelector('[data-chordpro-input]');
    const titleInput = root.querySelector('[data-song-title-input]');
    const artistInput = root.querySelector('[data-song-artist-input]');

    if (!chordProInput || !titleInput || !artistInput) {
        return;
    }

    const syncMetadata = () => {
        const chordPro = chordProInput.value;
        const title = extractChordProTag(chordPro, 'title');
        const artist = extractChordProTag(chordPro, 'artist');

        if (title) {
            titleInput.value = title;
        }

        if (artist) {
            artistInput.value = artist;
        }
    };

    chordProInput.addEventListener('input', syncMetadata);
    chordProInput.addEventListener('blur', syncMetadata);
    syncMetadata();
};

document.querySelectorAll('form').forEach(bootChordProForm);

const createLiveSetItem = (song) => {
    const item = document.createElement('li');
    item.className = 'live-set-item';
    item.dataset.songId = String(song.id);
    item.innerHTML = `
        <input type="hidden" name="songs[]" value="${song.id}">
        <span class="live-set-order-pill" data-live-position></span>
        <div class="live-set-item-copy">
            <strong>${song.title}</strong>
            <small>${song.artist} · ${song.default_key} · ${song.bpm} BPM</small>
        </div>
        <div class="live-set-item-actions">
            <button type="button" class="button subtle" data-live-up>Up</button>
            <button type="button" class="button subtle" data-live-down>Down</button>
            <button type="button" class="button danger-outline" data-live-remove>Remove</button>
        </div>
    `;

    return item;
};

const bootLiveSetBuilder = (root) => {
    const selection = root.querySelector('[data-live-selection]');
    const emptyState = root.querySelector('[data-live-empty]');
    const count = root.querySelector('[data-live-count]');

    if (!selection || !emptyState || !count) {
        return;
    }

    const sync = () => {
        const items = Array.from(selection.querySelectorAll('[data-song-id]'));

        items.forEach((item, index) => {
            const position = item.querySelector('[data-live-position]');

            if (position) {
                position.textContent = String(index + 1).padStart(2, '0');
            }
        });

        count.textContent = `${items.length} selected`;
        emptyState.classList.toggle('is-hidden', items.length > 0);

        root.querySelectorAll('[data-live-add]').forEach((button) => {
            const song = JSON.parse(button.dataset.song || '{}');
            const exists = selection.querySelector(`[data-song-id="${song.id}"]`);
            button.disabled = Boolean(exists);
            button.textContent = exists ? 'Added' : 'Add';
        });
    };

    root.addEventListener('click', (event) => {
        const addButton = event.target.closest('[data-live-add]');

        if (addButton) {
            const song = JSON.parse(addButton.dataset.song || '{}');

            if (!selection.querySelector(`[data-song-id="${song.id}"]`)) {
                selection.appendChild(createLiveSetItem(song));
                sync();
            }

            return;
        }

        const item = event.target.closest('.live-set-item');

        if (!item) {
            return;
        }

        if (event.target.closest('[data-live-remove]')) {
            item.remove();
            sync();
            return;
        }

        if (event.target.closest('[data-live-up]')) {
            const previous = item.previousElementSibling;

            if (previous) {
                selection.insertBefore(item, previous);
                sync();
            }

            return;
        }

        if (event.target.closest('[data-live-down]')) {
            const next = item.nextElementSibling;

            if (next) {
                selection.insertBefore(next, item);
                sync();
            }
        }
    });

    sync();
};

document.querySelectorAll('[data-live-set-builder]').forEach(bootLiveSetBuilder);
