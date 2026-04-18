<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $liveSet->name }} PDF</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #171411;
            --muted: #62584f;
            --line: rgba(87, 70, 49, 0.18);
            --accent: #0f5a4d;
            --accent-soft: rgba(15, 90, 77, 0.08);
            --paper: #fffdfa;
            --paper-soft: #f7f1e8;
            --danger: #bc4a34;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f2ede6;
            color: var(--ink);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .pdf-shell {
            max-width: 1080px;
            margin: 0 auto;
            padding: 40px 28px 56px;
        }

        .pdf-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
        }

        .pdf-brand {
            display: grid;
            gap: 6px;
        }

        .pdf-brand strong {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .pdf-brand span {
            color: var(--muted);
            font-size: 0.92rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        .pdf-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pdf-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 18px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
        }

        .pdf-button--primary {
            background: linear-gradient(135deg, #ff7b34, #ff5623);
            border-color: transparent;
            color: #fff;
        }

        .pdf-hero {
            display: grid;
            gap: 14px;
            margin-bottom: 26px;
            padding: 28px;
            border-radius: 28px;
            background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(250,245,238,0.94));
            border: 1px solid var(--line);
        }

        .pdf-hero p {
            margin: 0;
            color: #9d6a1b;
            font-size: 0.86rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .pdf-hero h1 {
            margin: 0;
            font-size: clamp(2.4rem, 5vw, 4rem);
            line-height: 0.95;
            letter-spacing: -0.05em;
            font-weight: 900;
        }

        .pdf-hero small {
            color: var(--muted);
            font-size: 1rem;
        }

        .pdf-song-list {
            display: grid;
            gap: 22px;
        }

        .pdf-song {
            display: grid;
            gap: 16px;
            padding: 24px;
            border-radius: 28px;
            background: var(--paper);
            border: 1px solid var(--line);
            break-inside: avoid;
        }

        .pdf-song-head {
            display: grid;
            gap: 10px;
        }

        .pdf-song-order {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            padding: 0 12px;
            border-radius: 999px;
            background: rgba(255, 141, 77, 0.16);
            color: #8f4623;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .pdf-song-head h2 {
            margin: 0;
            font-size: 2rem;
            line-height: 1;
            letter-spacing: -0.04em;
            font-weight: 900;
        }

        .pdf-song-head .song-meta {
            margin: 0;
            color: var(--muted);
            font-size: 0.92rem;
            text-transform: uppercase;
            letter-spacing: 0.14em;
        }

        .pdf-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .pdf-tags span {
            display: inline-flex;
            align-items: center;
            min-height: 34px;
            padding: 0 12px;
            border-radius: 999px;
            background: var(--paper-soft);
            border: 1px solid rgba(87, 70, 49, 0.1);
            color: var(--ink);
            font-size: 0.88rem;
            font-weight: 700;
        }

        .pdf-chart {
            display: grid;
            gap: 12px;
        }

        .pdf-section {
            display: grid;
            gap: 12px;
            padding: 18px;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(15, 90, 77, 0.05), rgba(255,255,255,0.92));
            border: 1px solid rgba(87, 70, 49, 0.1);
        }

        .pdf-section h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .pdf-lines {
            display: grid;
            gap: 10px;
            font-size: 1.02rem;
            line-height: 1.65;
        }

        .measure-line {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .measure {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            min-width: 48px;
            padding: 0 14px;
            border-radius: 14px;
            background: rgba(15, 90, 77, 0.06);
            border: 1px solid rgba(15, 90, 77, 0.12);
            font-weight: 800;
        }

        .chord {
            color: var(--accent);
            font-weight: 800;
        }

        .pdf-empty {
            color: var(--muted);
        }

        @media print {
            body {
                background: #fff;
            }

            .pdf-shell {
                max-width: none;
                padding: 0;
            }

            .pdf-toolbar {
                display: none;
            }

            .pdf-song,
            .pdf-hero {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    @php
        $barChordPattern = '/^\|?\s*[A-G][^|]*\|/';

        $renderLine = function (string $line) use ($barChordPattern): string {
            if (preg_match($barChordPattern, trim($line))) {
                $measures = collect(explode('|', $line))
                    ->map(fn (string $segment) => trim($segment))
                    ->filter()
                    ->map(fn (string $segment) => '<span class="measure">' . e($segment) . '</span>')
                    ->implode('');

                if ($measures !== '') {
                    return '<span class="measure-line">' . $measures . '</span>';
                }
            }

            $escaped = e($line);

            return preg_replace_callback(
                '/\[([^\]]+)\]/',
                fn (array $matches) => '<span class="chord">[' . e($matches[1]) . ']</span>',
                $escaped
            ) ?? $escaped;
        };
    @endphp

    <main class="pdf-shell">
        <div class="pdf-toolbar">
            <div class="pdf-brand">
                <strong>WorshipSync</strong>
                <span>Live Set Export</span>
            </div>
            <div class="pdf-actions">
                <button class="pdf-button pdf-button--primary" type="button" onclick="window.print()">Download PDF</button>
                <a class="pdf-button" href="{{ route('live-sets.show', $liveSet) }}">Back to Live Set</a>
            </div>
        </div>

        <section class="pdf-hero">
            <p>Live Set</p>
            <h1>{{ $liveSet->name }}</h1>
            <small>{{ $liveSet->songs->count() }} songs in service order. Use your browser's Save as PDF option after clicking Download PDF.</small>
        </section>

        <section class="pdf-song-list">
            @foreach ($previewSongs as $previewSong)
                <article class="pdf-song">
                    <div class="pdf-song-head">
                        <span class="pdf-song-order">Song {{ str_pad((string) $previewSong['order'], 2, '0', STR_PAD_LEFT) }}</span>
                        <p class="song-meta">{{ $previewSong['song']->artist ?: 'Unknown Artist' }}</p>
                        <h2>{{ $previewSong['song']->title }}</h2>
                        <div class="pdf-tags">
                            <span>{{ $previewSong['song']->default_key }}</span>
                            <span>{{ $previewSong['song']->bpm }} BPM</span>
                            <span>{{ $previewSong['song']->time_signature }}</span>
                        </div>
                    </div>

                    <div class="pdf-chart">
                        @forelse ($previewSong['chart']['sections'] as $section)
                            <article class="pdf-section">
                                <h3>{{ $section['name'] }}</h3>
                                <div class="pdf-lines">
                                    @foreach ($section['lines'] as $line)
                                        <div>{!! $renderLine($line) !!}</div>
                                    @endforeach
                                </div>
                            </article>
                        @empty
                            <article class="pdf-section pdf-empty">
                                <p>No chart lines were found for this song yet.</p>
                            </article>
                        @endforelse
                    </div>
                </article>
            @endforeach
        </section>
    </main>
</body>
</html>
