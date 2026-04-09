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
    <style>
        :root {
            --bg: #f5efe6;
            --panel: rgba(255, 250, 244, 0.82);
            --panel-strong: rgba(255, 252, 248, 0.95);
            --ink: #171411;
            --muted: #7a6f63;
            --line: rgba(71, 55, 37, 0.12);
            --accent: #0f5d50;
            --accent-strong: #083d35;
            --accent-soft: rgba(15, 93, 80, 0.08);
            --gold: #c58f35;
            --gold-soft: rgba(197, 143, 53, 0.18);
            --warning: #a64434;
            --shadow: 0 26px 80px rgba(39, 27, 15, 0.08);
            --shadow-deep: 0 40px 100px rgba(22, 16, 11, 0.14);
            --radius-xl: 34px;
            --radius-lg: 26px;
            --radius-md: 20px;
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
            font-family: Georgia, "Times New Roman", serif;
            background:
                radial-gradient(circle at 14% 10%, rgba(15, 93, 80, 0.2), transparent 24%),
                radial-gradient(circle at 88% 14%, rgba(197, 143, 53, 0.16), transparent 22%),
                linear-gradient(180deg, #fbf8f2 0%, var(--bg) 100%);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(rgba(255, 255, 255, 0.3) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.24) 1px, transparent 1px);
            background-size: 62px 62px;
            mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.34), transparent 76%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .shell {
            width: min(1360px, calc(100% - 44px));
            margin: 0 auto;
            padding: 24px 0 72px;
        }

        .content {
            display: grid;
            gap: 30px;
            min-width: 0;
        }

        .topbar,
        .topnav,
        .page-head,
        .player-head,
        .player-toolbar,
        .inline-actions,
        .hero-actions,
        .toolbar-group,
        .library-hero,
        .section-head,
        .library-hero-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar,
        .page-head,
        .player-head,
        .library-hero,
        .section-head {
            justify-content: space-between;
        }

        .topbar {
            position: sticky;
            top: 18px;
            z-index: 30;
            margin-bottom: 28px;
            padding: 18px 22px;
            background: rgba(255, 251, 246, 0.74);
            border: 1px solid rgba(71, 55, 37, 0.08);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 14px;
        }

        .brand-mark {
            display: inline-grid;
            place-items: center;
            width: 50px;
            height: 50px;
            border-radius: 18px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.18), transparent 60%),
                linear-gradient(135deg, var(--accent-strong), var(--accent));
            color: #fff9f0;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            box-shadow: 0 14px 30px rgba(15, 93, 80, 0.22);
        }

        .brand-text {
            display: grid;
            gap: 2px;
        }

        .brand-text strong {
            font-size: 1.52rem;
            line-height: 1;
            letter-spacing: -0.02em;
        }

        .brand-text small {
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-size: 0.66rem;
        }

        .topnav a:not(.button) {
            color: var(--muted);
            font-weight: 600;
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.38fr) minmax(320px, 0.7fr);
            gap: 26px;
            align-items: stretch;
        }

        .hero > div:first-child,
        .library-hero,
        .player-shell,
        .feature-card,
        .song-card,
        .stat-card,
        .panel,
        .empty-card,
        .flash,
        .error-box,
        .section-block {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
        }

        .hero > div:first-child {
            position: relative;
            overflow: hidden;
            padding: 46px;
            background:
                radial-gradient(circle at 82% 16%, rgba(255, 255, 255, 0.12), transparent 24%),
                linear-gradient(135deg, rgba(8, 58, 49, 0.99), rgba(15, 93, 80, 0.94) 56%, rgba(197, 143, 53, 0.9));
            color: #fff8ef;
            box-shadow: var(--shadow-deep);
        }

        .hero > div:first-child::before,
        .library-hero::after {
            content: "";
            position: absolute;
            border-radius: 999px;
        }

        .hero > div:first-child::before {
            width: 360px;
            height: 360px;
            right: -88px;
            top: -70px;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 64px rgba(255, 255, 255, 0.04);
        }

        .hero > div:first-child > * {
            position: relative;
            z-index: 1;
        }

        .library-hero {
            position: relative;
            overflow: hidden;
            padding: 30px 34px;
            background:
                linear-gradient(135deg, rgba(255, 252, 248, 0.96), rgba(244, 239, 230, 0.88));
        }

        .library-hero::after {
            width: 240px;
            height: 240px;
            right: -80px;
            top: -110px;
            background: radial-gradient(circle, rgba(15, 93, 80, 0.12), transparent 70%);
        }

        .library-hero-copy,
        .page-head > div {
            max-width: 880px;
            min-width: 0;
        }

        .hero h1,
        .page-head h1,
        .player-head h1,
        .library-hero h1 {
            margin: 8px 0 0;
            max-width: 10ch;
            font-size: clamp(2.35rem, 5vw, 4.8rem);
            line-height: 0.9;
            letter-spacing: -0.055em;
        }

        .section-head h2,
        .feature-card h2,
        .song-card h2,
        .empty-card h2,
        .studio-side h2 {
            margin: 0;
            font-size: 1.42rem;
            line-height: 1.08;
        }

        .lead,
        .section-lead,
        .song-card p,
        .feature-card li,
        .empty-card p,
        .player-head p,
        .studio-side p {
            color: var(--muted);
            line-height: 1.75;
        }

        .lead {
            margin: 24px 0 0;
            color: rgba(255, 248, 239, 0.88);
            font-size: 1.1rem;
        }

        .section-lead {
            margin: 10px 0 0;
            max-width: 34rem;
            font-size: 0.98rem;
            line-height: 1.62;
        }

        .eyebrow {
            margin: 0;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .feature-card,
        .song-card,
        .stat-card,
        .panel,
        .empty-card,
        .flash,
        .error-box,
        .section-block,
        .player-shell {
            padding: 22px;
        }

        .feature-card {
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(180deg, rgba(255, 252, 248, 0.95), rgba(247, 241, 232, 0.94));
        }

        .feature-card::before {
            content: "Set Ready";
            position: absolute;
            top: 18px;
            right: 18px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(15, 93, 80, 0.08);
            color: var(--accent-strong);
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.14em;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            border-radius: 999px;
            padding: 13px 20px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.08), transparent 60%),
                linear-gradient(135deg, var(--accent), var(--accent-strong));
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            letter-spacing: 0.01em;
            box-shadow: 0 18px 34px rgba(15, 93, 80, 0.2);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .button:hover {
            transform: translateY(-1px);
            box-shadow: 0 22px 42px rgba(15, 93, 80, 0.25);
        }

        .button.ghost {
            background: rgba(255, 255, 255, 0.56);
            color: var(--accent-strong);
            border: 1px solid rgba(15, 93, 80, 0.16);
            box-shadow: none;
        }

        .button.subtle {
            background: rgba(232, 223, 209, 0.76);
            color: var(--ink);
            box-shadow: none;
        }

        .button.danger {
            background: linear-gradient(135deg, #b55242, var(--warning));
        }

        .button.danger-outline {
            background: transparent;
            color: var(--warning);
            border: 1px solid rgba(166, 68, 52, 0.24);
            box-shadow: none;
        }

        .button.danger-outline:hover {
            background: rgba(166, 68, 52, 0.08);
        }

        .stats-grid,
        .song-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            align-items: start;
        }

        .stat-card {
            min-height: 148px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.9), rgba(244, 237, 227, 0.88));
        }

        .stat-card span {
            display: block;
            color: var(--muted);
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
        }

        .stat-card strong {
            display: block;
            margin-top: 14px;
            max-width: 12ch;
            font-size: clamp(1.65rem, 2.5vw, 2.5rem);
            line-height: 1;
            letter-spacing: -0.04em;
        }

        .section-block {
            display: grid;
            gap: 16px;
        }

        .song-card {
            display: grid;
            gap: 12px;
            position: relative;
            overflow: hidden;
            min-height: 190px;
            background:
                linear-gradient(180deg, rgba(255, 252, 248, 0.96), rgba(246, 239, 229, 0.92));
        }

        .song-card::after {
            content: "";
            position: absolute;
            width: 140px;
            height: 140px;
            right: -38px;
            bottom: -38px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(15, 93, 80, 0.12), transparent 70%);
        }

        .song-meta {
            margin: 0;
            color: var(--muted);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
        }

        .song-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .song-tags span {
            padding: 8px 11px;
            border-radius: 999px;
            background: rgba(15, 93, 80, 0.08);
            color: var(--accent-strong);
            font-size: 0.78rem;
            font-weight: 700;
        }

        .empty-card {
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 12px;
            padding: 30px;
            background:
                linear-gradient(135deg, rgba(255, 252, 248, 0.98), rgba(245, 236, 221, 0.92));
        }

            .studio-shell {
                display: grid;
                grid-template-columns: minmax(0, 1.18fr) minmax(340px, 0.82fr);
                gap: 28px;
                align-items: start;
            }

            .studio-shell-single {
                grid-template-columns: minmax(0, 980px);
                justify-content: start;
            }

            .studio-main {
                display: grid;
                gap: 22px;
                padding: 22px;
                background:
                    linear-gradient(180deg, rgba(255, 252, 248, 0.96), rgba(246, 239, 229, 0.92));
            }

        .studio-grid {
            display: grid;
            gap: 24px;
        }

        .metadata-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .metadata-grid label,
        .chart-field {
            display: grid;
            gap: 10px;
            color: var(--muted);
            font-weight: 600;
        }

        .chart-field {
            gap: 12px;
        }

        .metadata-grid label span,
        .chart-field span {
            font-size: 0.92rem;
            color: #51483f;
        }

        input,
        textarea {
            width: 100%;
            border: 1px solid rgba(71, 55, 37, 0.1);
            border-radius: 18px;
            padding: 14px 15px;
            font: inherit;
            color: var(--ink);
            background: rgba(255, 255, 255, 0.84);
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
        }

        input {
            min-height: 54px;
        }

        textarea {
            min-height: 340px;
            resize: vertical;
            line-height: 1.72;
            font-size: 1rem;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: rgba(15, 93, 80, 0.34);
            box-shadow: 0 0 0 5px rgba(15, 93, 80, 0.08);
            background: rgba(255, 255, 255, 0.97);
        }

        small {
            color: var(--muted);
            font-weight: 400;
            font-size: 0.88rem;
            line-height: 1.55;
        }

        .studio-actions,
        .danger-zone {
            display: flex;
            justify-content: flex-start;
        }

        .player-shell {
            display: grid;
            gap: 18px;
        }

        .player-toolbar,
        .chart-panel {
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            background: rgba(255, 255, 255, 0.88);
            padding: 18px;
        }

        .chart-panel {
            display: grid;
            gap: 18px;
        }

        .chart-section {
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(71, 55, 37, 0.1);
        }

        .chart-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

            .chart-lines {
                display: grid;
                gap: 12px;
                white-space: pre-wrap;
                font-size: 1.04rem;
                line-height: 1.82;
            }

            .measure-line {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .measure {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 72px;
                padding: 8px 14px;
                border-radius: 14px;
                background: rgba(15, 93, 80, 0.08);
                border: 1px solid rgba(15, 93, 80, 0.12);
                color: var(--accent-strong);
                font-weight: 700;
                line-height: 1.2;
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
            width: 46px;
            height: 46px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent), var(--accent-strong));
            color: #fff;
            font-weight: 700;
        }

        .pagination-wrap nav,
        .pagination-wrap > div {
            display: flex;
            justify-content: center;
        }

        .flash,
        .error-box {
            padding: 18px 24px;
        }

        .flash {
            color: var(--accent-strong);
            background: linear-gradient(180deg, rgba(220, 238, 231, 0.96), rgba(255, 252, 247, 0.92));
        }

        .error-box {
            color: var(--warning);
            background: linear-gradient(180deg, rgba(255, 242, 239, 0.96), rgba(255, 252, 247, 0.92));
        }

        .error-box ul,
        .feature-card ul {
            margin: 0;
            padding-left: 1.2rem;
        }

            @media (max-width: 1024px) {
                .studio-shell,
                .hero {
                    grid-template-columns: 1fr;
                }

                .studio-shell-single {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 840px) {
                .shell {
                    width: min(100% - 20px, 100%);
                    padding: 14px 0 40px;
                }

                .topbar,
                .page-head,
            .player-head,
            .player-toolbar,
            .library-hero,
                .section-head {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .topbar {
                    position: static;
                    gap: 14px;
                    padding: 16px;
                    margin-bottom: 18px;
                    border-radius: 24px;
                }

                .brand {
                    width: 100%;
                    align-items: flex-start;
                }

                .brand-mark {
                    width: 44px;
                    height: 44px;
                    border-radius: 14px;
                }

                .brand-text strong {
                    font-size: 1.2rem;
                }

                .brand-text small {
                    font-size: 0.62rem;
                    letter-spacing: 0.1em;
                }

                .topnav {
                    width: 100%;
                    flex-wrap: wrap;
                    gap: 10px;
                }

                .topnav a,
                .topnav form {
                    width: 100%;
                }

                .topnav .button,
                .topnav a:not(.button) {
                    width: 100%;
                    justify-content: center;
                }

                .metadata-grid {
                    grid-template-columns: 1fr;
                }

                .hero h1,
                .page-head h1,
                .player-head h1,
                .library-hero h1 {
                    font-size: clamp(2.2rem, 12vw, 3.4rem);
                    line-height: 0.95;
                }

                .lead,
                .section-lead {
                    font-size: 0.98rem;
                    line-height: 1.6;
                }

                .stats-grid,
                .song-grid {
                    grid-template-columns: 1fr;
                }

                .feature-card::before {
                    position: static;
                    display: inline-flex;
                    margin-bottom: 14px;
                }

                .button,
                .inline-actions .button,
                .hero-actions .button,
                .library-hero-actions .button,
                .studio-actions .button,
                .danger-zone .button {
                    width: 100%;
                }

                .inline-actions,
                .hero-actions,
                .library-hero-actions,
                .studio-actions,
                .danger-zone,
                .player-toolbar,
                .toolbar-group {
                    width: 100%;
                    flex-direction: column;
                    align-items: stretch;
                }

                .song-tags {
                    gap: 8px;
                }

                .song-tags span {
                    width: 100%;
                    justify-content: center;
                    text-align: center;
                }

                .hero > div:first-child,
                .library-hero,
                .panel,
                .song-card,
                .feature-card,
                .empty-card,
                .studio-main {
                    padding: 22px;
                }

                .studio-main,
                .player-shell {
                    padding: 20px;
                }

                textarea {
                    min-height: 320px;
                }

                input,
                textarea,
                select {
                    font-size: 16px;
                }
            }

            @media (max-width: 520px) {
                body::before {
                    background-size: 40px 40px;
                }

                .shell {
                    width: calc(100% - 16px);
                    padding: 10px 0 28px;
                }

                .topbar,
                .hero > div:first-child,
                .library-hero,
                .panel,
                .song-card,
                .feature-card,
                .empty-card,
                .flash,
                .error-box,
                .section-block,
                .player-shell {
                    border-radius: 22px;
                }

                .hero > div:first-child,
                .library-hero,
                .panel,
                .song-card,
                .feature-card,
                .empty-card,
                .studio-main,
                .player-shell {
                    padding: 18px;
                }

                .hero h1,
                .page-head h1,
                .player-head h1,
                .library-hero h1 {
                    font-size: clamp(1.95rem, 11vw, 2.8rem);
                }

                .section-head h2,
                .feature-card h2,
                .song-card h2,
                .empty-card h2 {
                    font-size: 1.35rem;
                }

                .button {
                    padding: 13px 18px;
                }

                .chart-lines {
                    font-size: 0.96rem;
                    line-height: 1.65;
                }

                .measure {
                    min-width: 58px;
                    padding: 7px 12px;
                }
            }
        </style>
    @if ($hasViteBuild)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <div class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('welcome') }}">
                <span class="brand-mark">WS</span>
                <span class="brand-text">
                    <strong>WorshipSync</strong>
                    <small>Setlist Studio</small>
                </span>
            </a>

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
