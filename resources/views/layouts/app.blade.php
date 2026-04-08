<!DOCTYPE html>
<html lang="en">
@php
    $hasViteBuild = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'WorshipSync' }}</title>
    @if ($hasViteBuild)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            :root {
                --bg: #f4efe6;
                --panel: rgba(255, 252, 247, 0.9);
                --ink: #1a1713;
                --muted: #6f6559;
                --line: rgba(126, 104, 78, 0.18);
                --accent: #18685a;
                --accent-strong: #0f4d42;
                --accent-soft: #dceee7;
                --gold: #c8943d;
                --warning: #a34130;
                --shadow: 0 24px 60px rgba(39, 26, 14, 0.08);
            }

            * {
                box-sizing: border-box;
            }

            html {
                scroll-behavior: smooth;
            }

            body {
                margin: 0;
                min-height: 100vh;
                color: var(--ink);
                font-family: "Georgia", "Times New Roman", serif;
                background:
                    radial-gradient(circle at top left, rgba(24, 104, 90, 0.22), transparent 26%),
                    radial-gradient(circle at bottom right, rgba(200, 148, 61, 0.18), transparent 28%),
                    linear-gradient(180deg, #fbf7f1 0%, var(--bg) 100%);
            }

            body::before {
                content: "";
                position: fixed;
                inset: 0;
                pointer-events: none;
                background-image:
                    linear-gradient(rgba(255, 255, 255, 0.35) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255, 255, 255, 0.3) 1px, transparent 1px);
                background-size: 48px 48px;
                mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.25), transparent 70%);
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            .shell {
                width: min(1180px, calc(100% - 32px));
                margin: 0 auto;
                padding: 20px 0 64px;
            }

            .topbar,
            .topnav,
            .page-head,
            .player-head,
            .player-toolbar,
            .inline-actions,
            .hero-actions,
            .toolbar-group {
                display: flex;
                align-items: center;
                gap: 14px;
            }

            .topbar,
            .page-head,
            .player-head {
                justify-content: space-between;
            }

            .topbar {
                position: sticky;
                top: 14px;
                z-index: 20;
                margin-bottom: 28px;
                padding: 14px 18px;
                background: rgba(255, 252, 247, 0.72);
                border: 1px solid rgba(126, 104, 78, 0.14);
                border-radius: 24px;
                box-shadow: var(--shadow);
                backdrop-filter: blur(16px);
            }

            .brand {
                font-size: 1.45rem;
                font-weight: 700;
                letter-spacing: 0.03em;
            }

            .content {
                display: grid;
                gap: 24px;
            }

            .hero {
                display: grid;
                grid-template-columns: minmax(0, 1.4fr) minmax(320px, 0.8fr);
                gap: 24px;
                align-items: stretch;
            }

            .hero > div:first-child {
                position: relative;
                overflow: hidden;
                padding: 36px;
                border-radius: 34px;
                border: 1px solid var(--line);
                background:
                    linear-gradient(135deg, rgba(17, 71, 61, 0.96), rgba(29, 111, 95, 0.92) 56%, rgba(200, 148, 61, 0.9));
                color: #fff9f0;
                box-shadow: var(--shadow);
            }

            .hero > div:first-child::after {
                content: "";
                position: absolute;
                width: 280px;
                height: 280px;
                border-radius: 999px;
                right: -60px;
                top: -60px;
                background: rgba(255, 255, 255, 0.12);
                box-shadow: 0 0 0 40px rgba(255, 255, 255, 0.05);
            }

            .hero > div:first-child > * {
                position: relative;
                z-index: 1;
            }

            .hero h1,
            .page-head h1,
            .player-head h1 {
                margin: 8px 0 0;
                font-size: clamp(2.3rem, 4vw, 4.5rem);
                line-height: 0.98;
                letter-spacing: -0.03em;
            }

            .lead {
                margin: 24px 0 0;
                max-width: 760px;
                color: rgba(255, 249, 240, 0.88);
                font-size: 1.08rem;
                line-height: 1.8;
            }

            .eyebrow {
                margin: 0;
                color: var(--gold);
                text-transform: uppercase;
                letter-spacing: 0.18em;
                font-size: 0.74rem;
                font-weight: 700;
            }

            .feature-card,
            .song-card,
            .stat-card,
            .panel,
            .player-shell,
            .notes-panel,
            .empty-card,
            .flash,
            .error-box {
                background: var(--panel);
                border: 1px solid var(--line);
                border-radius: 28px;
                box-shadow: var(--shadow);
                backdrop-filter: blur(10px);
            }

            .feature-card,
            .song-card,
            .stat-card,
            .empty-card,
            .notes-panel,
            .flash,
            .error-box,
            .panel,
            .player-shell {
                padding: 24px;
            }

            .feature-card {
                position: relative;
                overflow: hidden;
            }

            .feature-card::before {
                content: "Set Ready";
                position: absolute;
                top: 18px;
                right: 18px;
                padding: 8px 12px;
                border-radius: 999px;
                background: var(--accent-soft);
                color: var(--accent-strong);
                font-size: 0.75rem;
                font-weight: 700;
                letter-spacing: 0.12em;
                text-transform: uppercase;
            }

            .feature-card h2,
            .song-card h2,
            .notes-panel h2,
            .empty-card h2 {
                margin: 0 0 16px;
                font-size: 1.6rem;
            }

            .feature-card ul,
            .error-box ul {
                margin: 0;
                padding-left: 20px;
            }

            .feature-card li,
            .song-card p,
            .notes-panel p,
            .empty-card p,
            .song-meta {
                color: var(--muted);
                line-height: 1.7;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                border: none;
                border-radius: 999px;
                padding: 14px 22px;
                background: linear-gradient(135deg, var(--accent), var(--accent-strong));
                color: #fff;
                cursor: pointer;
                font: inherit;
                font-weight: 700;
                letter-spacing: 0.01em;
                box-shadow: 0 14px 32px rgba(24, 104, 90, 0.24);
                transition: transform 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
            }

            .button:hover {
                transform: translateY(-1px);
                box-shadow: 0 18px 38px rgba(24, 104, 90, 0.3);
            }

            .button.ghost {
                background: transparent;
                color: var(--accent-strong);
                border: 1px solid rgba(24, 104, 90, 0.22);
                box-shadow: none;
            }

            .button.subtle {
                background: #ece3d6;
                color: var(--ink);
                box-shadow: none;
            }

            .button.danger {
                background: linear-gradient(135deg, #b14c36, var(--warning));
            }

            .stats-grid,
            .song-grid {
                display: grid;
                gap: 18px;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }

            .stat-card {
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.9), rgba(246, 237, 223, 0.88));
            }

            .stat-card span {
                display: block;
                color: var(--muted);
                font-size: 0.82rem;
                text-transform: uppercase;
                letter-spacing: 0.14em;
            }

            .stat-card strong {
                display: block;
                margin-top: 10px;
                font-size: 2rem;
            }

            .song-card {
                position: relative;
                overflow: hidden;
            }

            .song-card::after {
                content: "";
                position: absolute;
                inset: auto -20px -20px auto;
                width: 90px;
                height: 90px;
                border-radius: 999px;
                background: radial-gradient(circle, rgba(24, 104, 90, 0.14), transparent 70%);
            }

            .song-meta {
                margin-bottom: 6px;
                font-size: 0.78rem;
                text-transform: uppercase;
                letter-spacing: 0.12em;
            }

            .form-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 18px;
            }

            .form-grid label {
                display: grid;
                gap: 8px;
                color: var(--muted);
                font-weight: 600;
            }

            .form-grid .full {
                grid-column: 1 / -1;
            }

            input,
            textarea {
                width: 100%;
                border: 1px solid rgba(126, 104, 78, 0.16);
                border-radius: 18px;
                padding: 15px 16px;
                font: inherit;
                color: var(--ink);
                background: rgba(255, 255, 255, 0.92);
                transition: border-color 0.18s ease, box-shadow 0.18s ease;
            }

            input:focus,
            textarea:focus {
                outline: none;
                border-color: rgba(24, 104, 90, 0.42);
                box-shadow: 0 0 0 4px rgba(24, 104, 90, 0.09);
            }

            small {
                color: var(--muted);
                font-weight: 400;
            }

            .danger-zone {
                display: flex;
                justify-content: flex-end;
            }

            .player-shell {
                display: grid;
                gap: 18px;
            }

            .player-toolbar,
            .structure-strip,
            .chart-panel {
                border: 1px solid var(--line);
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.9);
                padding: 18px;
            }

            .structure-strip {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .structure-strip span {
                padding: 10px 14px;
                border-radius: 999px;
                background: var(--accent-soft);
                color: var(--accent-strong);
                font-weight: 700;
            }

            .chart-panel {
                display: grid;
                gap: 18px;
            }

            .chart-section {
                padding-bottom: 18px;
                border-bottom: 1px solid #eee4d7;
            }

            .chart-section:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .chart-lines {
                display: grid;
                gap: 10px;
                white-space: pre-wrap;
                font-size: 1.02rem;
            }

            .chord {
                color: var(--accent-strong);
                font-weight: 700;
            }

            .beat-indicator {
                margin-left: auto;
            }

            .beat-indicator span {
                display: inline-grid;
                place-items: center;
                width: 44px;
                height: 44px;
                border-radius: 999px;
                background: linear-gradient(135deg, var(--accent), var(--accent-strong));
                color: white;
                font-weight: 700;
            }

            .pagination-wrap nav,
            .pagination-wrap > div {
                display: flex;
                justify-content: center;
            }

            .flash {
                color: var(--accent-strong);
                background: linear-gradient(180deg, rgba(220, 238, 231, 0.96), rgba(255, 252, 247, 0.9));
            }

            .error-box {
                color: var(--warning);
                background: linear-gradient(180deg, rgba(255, 242, 239, 0.95), rgba(255, 252, 247, 0.9));
            }

            @media (max-width: 900px) {
                .hero,
                .form-grid {
                    grid-template-columns: 1fr;
                }

                .topbar,
                .page-head,
                .player-head,
                .player-toolbar {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .topnav {
                    flex-wrap: wrap;
                }

                .hero > div:first-child {
                    padding: 28px;
                }
            }
        </style>
    @endif
</head>
<body>
    <div class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('welcome') }}">WorshipSync</a>

            <nav class="topnav">
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('songs.index') }}">Songs</a>
                    <a class="button ghost" href="{{ route('songs.create') }}">New Song</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="button subtle">Logout</button>
                    </form>
                @else
                    <a class="button" href="{{ route('google.redirect') }}">Sign in with Google</a>
                @endauth
            </nav>
        </header>

        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        <main class="content">
            @yield('content')
        </main>
    </div>
</body>
</html>
