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

const renderLine = (line, steps) =>
    line.replace(/\[([^\]]+)\]/g, (_, chord) => `<span class="chord">[${transposeChord(chord, steps)}]</span>`);

const bootSongPlayer = (root) => {
    const chart = JSON.parse(root.dataset.chart || '{"sections": []}');
    const defaultKey = root.dataset.defaultKey;
    let bpm = Number(root.dataset.bpm || 72);
    const beatsPerBar = Number(root.dataset.beatsPerBar || 4);
    let transpose = 0;
    let metronomeTimer = null;
    let beat = 0;
    const tapHistory = [];
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();

    const chartOutput = root.querySelector('[data-chart-output]');
    const currentKey = root.querySelector('[data-current-key]');
    const bpmDisplay = root.querySelector('[data-bpm-display]');
    const beatIndicator = root.querySelector('[data-beat-indicator]');
    const toggle = root.querySelector('[data-metronome-toggle]');

    const render = () => {
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
