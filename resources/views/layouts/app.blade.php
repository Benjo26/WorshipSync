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
            --bg: #efe8db;
            --panel: rgba(255, 252, 247, 0.78);
            --panel-strong: rgba(255, 253, 249, 0.96);
            --ink: #16120f;
            --muted: #706558;
            --line: rgba(76, 58, 36, 0.1);
            --accent: #0f5a4d;
            --accent-strong: #083a33;
            --accent-soft: rgba(15, 90, 77, 0.08);
            --gold: #b9852f;
            --gold-soft: rgba(197, 143, 53, 0.18);
            --warning: #a64434;
            --shadow: 0 22px 60px rgba(34, 25, 15, 0.07);
            --shadow-deep: 0 40px 100px rgba(22, 16, 11, 0.14);
            --radius-xl: 34px;
            --radius-lg: 26px;
            --radius-md: 20px;
            --font-display: "Avenir Next", "Segoe UI", "Helvetica Neue", Arial, sans-serif;
            --font-body: "Avenir Next", "Segoe UI", "Helvetica Neue", Arial, sans-serif;
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
            font-family: var(--font-body);
            background:
                radial-gradient(circle at 8% 10%, rgba(15, 90, 77, 0.18), transparent 24%),
                radial-gradient(circle at 92% 16%, rgba(185, 133, 47, 0.14), transparent 22%),
                linear-gradient(180deg, #f8f4ed 0%, var(--bg) 100%);
        }

        body.landing-body {
            background:
                radial-gradient(circle at 72% 18%, rgba(255, 92, 36, 0.18), transparent 22%),
                radial-gradient(circle at 18% 20%, rgba(15, 93, 80, 0.18), transparent 26%),
                linear-gradient(180deg, #090909 0%, #050505 100%);
            color: #f5efe7;
        }

        body.app-body {
            background:
                radial-gradient(circle at 18% 12%, rgba(15, 93, 80, 0.14), transparent 24%),
                radial-gradient(circle at 82% 18%, rgba(255, 107, 43, 0.08), transparent 22%),
                linear-gradient(180deg, #0b0d0c 0%, #111513 100%);
            color: #f2eee7;
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

        body.landing-body::before {
            background:
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 72px 72px;
            mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.45), transparent 82%);
        }

        body.app-body::before {
            background:
                linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 72px 72px;
            mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.44), transparent 82%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        body,
        button,
        input,
        textarea,
        select {
            font-family: var(--font-body);
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
            padding: 14px 16px;
            background:
                linear-gradient(180deg, rgba(14, 33, 31, 0.95), rgba(20, 47, 41, 0.9));
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-xl);
            box-shadow: 0 26px 70px rgba(13, 19, 18, 0.26);
            backdrop-filter: blur(18px);
        }

        .topbar > .topnav {
            flex: 1 1 auto;
            min-width: 0;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            flex: 0 0 auto;
        }

        .brand-mark {
            display: inline-grid;
            place-items: center;
            width: 50px;
            height: 50px;
            border-radius: 18px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.18), transparent 60%),
                linear-gradient(135deg, #0d6959, #0a3e36);
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
            letter-spacing: -0.025em;
            color: #fffaf3;
            font-family: var(--font-body);
            font-weight: 800;
        }

        .brand-text small {
            color: rgba(245, 236, 225, 0.62);
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-size: 0.66rem;
        }

        .brand-text strong,
        .landing-hero h1,
        .hero h1,
        .page-head h1,
        .player-head h1,
        .library-hero h1,
        .section-head h2,
        .feature-card h2,
        .song-card h2,
        .live-set-card h2,
        .empty-card h2,
        .studio-side h2,
        .chart-section h2,
        .live-song-option h3,
        .live-set-preview-copy h2,
        .stat-card strong,
        .control-value,
        .tempo-readout {
            font-family: var(--font-body);
            font-variation-settings: normal;
            text-rendering: optimizeLegibility;
        }

        .eyebrow,
        .song-meta,
        .control-label,
        .brand-text small,
        .player-head p,
        .section-lead,
        .song-card p,
        .live-set-card p,
        .empty-card p,
        .chart-field span,
        .metadata-grid label span {
            font-family: var(--font-body);
            letter-spacing: 0.08em;
        }

        .topnav a:not(.button) {
            color: rgba(245, 236, 225, 0.78);
            font-weight: 600;
        }

        .topnav {
            justify-content: flex-end;
            flex-wrap: nowrap;
            min-width: 0;
        }

        .topnav a,
        .topnav form {
            flex: 0 0 auto;
            min-width: 0;
        }

        .topnav a:not(.button),
        .topnav .button,
        .topnav form .button {
            min-height: 56px;
            white-space: nowrap;
        }

        body.landing-body .topbar {
            background: rgba(16, 16, 16, 0.92);
            border-color: rgba(255, 255, 255, 0.08);
        }

        body.app-body .topbar {
            background:
                linear-gradient(180deg, rgba(17, 20, 19, 0.96), rgba(23, 29, 26, 0.92));
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 28px 80px rgba(0, 0, 0, 0.35);
        }

        body.app-body .topnav a:not(.button) {
            color: rgba(246, 239, 229, 0.76);
        }

        body.landing-body .topnav {
            padding: 6px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        body.landing-body .topnav .button {
            box-shadow: none;
        }

        body.landing-body .topnav .button.ghost {
            background: linear-gradient(135deg, #ff7b34, #ff5623);
            color: #fff;
            border: none;
        }

        body.landing-body .topnav .button.subtle {
            background: rgba(255, 255, 255, 0.08);
            color: #f5efe7;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.38fr) minmax(320px, 0.7fr);
            gap: 26px;
            align-items: stretch;
        }

        .landing-hero {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(360px, 0.95fr);
            gap: 32px;
            align-items: center;
            min-height: calc(100vh - 170px);
            padding: 48px 8px 20px;
        }

        .landing-copy {
            display: grid;
            gap: 20px;
            max-width: 660px;
            align-self: center;
        }

        .landing-kicker {
            margin: 0;
            color: rgba(255, 255, 255, 0.56);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .landing-hero h1 {
            margin: 0;
            max-width: 10ch;
            color: #fff8f1;
            font-size: clamp(3.4rem, 7vw, 6.6rem);
            line-height: 0.92;
            letter-spacing: -0.06em;
            font-family: var(--font-body);
            font-weight: 800;
        }

        .landing-hero h1::first-line {
            color: #ff6a2b;
        }

        .landing-lead {
            margin: 0;
            max-width: 32rem;
            color: rgba(255, 245, 235, 0.74);
            font-size: 1.12rem;
            line-height: 1.72;
        }

        .landing-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .landing-primary {
            background: linear-gradient(135deg, #ff7b34, #ff5623);
            box-shadow: 0 24px 44px rgba(255, 96, 36, 0.24);
        }

        .landing-secondary {
            background: rgba(255, 255, 255, 0.06);
            color: #fff4eb;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: none;
        }

        .landing-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .landing-stats span {
            display: inline-flex;
            align-items: center;
            min-height: 38px;
            padding: 0 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 247, 239, 0.8);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .landing-visual {
            position: relative;
            min-height: 640px;
            display: grid;
            place-items: end center;
        }

        .landing-orb {
            position: absolute;
            right: 20px;
            bottom: 14px;
            width: min(540px, 100%);
            aspect-ratio: 1 / 1.08;
            border-radius: 999px 999px 44px 44px;
            background: radial-gradient(circle at 50% 10%, rgba(255, 117, 54, 0.3), rgba(103, 35, 16, 0.7) 70%, rgba(0, 0, 0, 0) 72%);
            filter: blur(0.5px);
        }

        .landing-device {
            position: relative;
            z-index: 1;
            width: min(560px, 100%);
            border-radius: 34px;
            padding: 14px;
            background: linear-gradient(180deg, rgba(24, 24, 24, 0.94), rgba(12, 12, 12, 0.98));
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 40px 120px rgba(0, 0, 0, 0.5);
        }

        .landing-device-top {
            display: flex;
            gap: 8px;
            padding: 6px 6px 12px;
        }

        .landing-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.15);
        }

        .landing-device-body {
            position: relative;
            display: grid;
            gap: 16px;
            min-height: 520px;
            padding: 22px;
            border-radius: 24px;
            background:
                radial-gradient(circle at 80% 16%, rgba(255, 106, 43, 0.12), transparent 24%),
                linear-gradient(180deg, rgba(20, 20, 20, 0.98), rgba(10, 10, 10, 1));
            overflow: hidden;
        }

        .landing-panel {
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .landing-panel-primary {
            padding: 24px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.07), rgba(255, 255, 255, 0.04));
        }

        .landing-panel-primary strong,
        .landing-floating-card strong {
            display: block;
            color: #fff8f1;
            font-size: 1.5rem;
            letter-spacing: -0.03em;
        }

        .landing-chip-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
        }

        .landing-chip-row span {
            display: inline-flex;
            align-items: center;
            min-height: 38px;
            padding: 0 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 245, 235, 0.84);
            font-size: 0.9rem;
            font-weight: 700;
        }

        .landing-panel-secondary {
            display: grid;
            gap: 18px;
            padding: 22px;
            background: linear-gradient(180deg, rgba(255, 93, 35, 0.14), rgba(255, 255, 255, 0.03));
        }

        .landing-mini-meter {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            align-items: end;
            min-height: 96px;
        }

        .landing-mini-meter span {
            border-radius: 18px 18px 10px 10px;
            background: linear-gradient(180deg, rgba(255, 122, 56, 0.9), rgba(255, 89, 33, 0.38));
        }

        .landing-mini-meter span:nth-child(1) { height: 54px; }
        .landing-mini-meter span:nth-child(2) { height: 82px; }
        .landing-mini-meter span:nth-child(3) { height: 66px; }
        .landing-mini-meter span:nth-child(4) { height: 94px; }

        .landing-mini-copy p {
            margin: 0;
            max-width: 22rem;
            color: rgba(255, 245, 235, 0.74);
            line-height: 1.65;
        }

        .landing-floating-card {
            position: absolute;
            display: grid;
            gap: 4px;
            min-width: 170px;
            padding: 14px 16px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(18px);
        }

        .landing-floating-left {
            left: 22px;
            bottom: 30px;
        }

        .landing-floating-right {
            right: 22px;
            top: 210px;
        }

        .landing-floating-card small {
            color: rgba(255, 255, 255, 0.56);
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-size: 0.68rem;
        }

        body.app-body .library-hero,
        body.app-body .player-shell,
        body.app-body .feature-card,
        body.app-body .song-card,
        body.app-body .panel,
        body.app-body .empty-card,
        body.app-body .flash,
        body.app-body .error-box,
        body.app-body .section-block {
            background: linear-gradient(180deg, rgba(23, 28, 26, 0.88), rgba(18, 22, 20, 0.92));
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 56px rgba(0, 0, 0, 0.22);
        }

        body.app-body .library-hero::after,
        body.app-body .song-card::after {
            background: radial-gradient(circle, rgba(255, 107, 43, 0.16), transparent 72%);
        }

        body.app-body .hero h1,
        body.app-body .page-head h1,
        body.app-body .player-head h1,
        body.app-body .library-hero h1,
        body.app-body .section-head h2,
        body.app-body .song-card h2,
        body.app-body .empty-card h2 {
            color: #fff8ef;
        }

        body.app-body .section-lead,
        body.app-body .song-card p,
        body.app-body .empty-card p,
        body.app-body .player-head p,
        body.app-body .song-meta,
        body.app-body small,
        body.app-body .metadata-grid label span,
        body.app-body .chart-field span {
            color: rgba(236, 226, 214, 0.68);
        }

        body.app-body .section-head,
        body.app-body .chart-section {
            border-color: rgba(255, 255, 255, 0.08);
        }

        body.app-body .search-input input,
        body.app-body input,
        body.app-body textarea {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.08);
            color: #fff8ef;
        }

        body.app-body input::placeholder,
        body.app-body textarea::placeholder {
            color: rgba(236, 226, 214, 0.38);
        }

        body.app-body input:focus,
        body.app-body textarea:focus {
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 0 0 5px rgba(255, 107, 43, 0.08);
            border-color: rgba(255, 107, 43, 0.26);
        }

        body.app-body .song-tags span,
        body.app-body .player-meta span,
        body.app-body .measure,
        body.app-body .beat-pill,
        body.app-body .preview-meta span {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.08);
            color: #f2eee7;
        }

        body.app-body .button.ghost {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff8ef;
        }

        body.app-body .button.subtle {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 245, 235, 0.84);
        }

        body.app-body .button {
            box-shadow: 0 18px 36px rgba(255, 107, 43, 0.14);
        }

        body.app-body .topnav .global-search-form .search-input input {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.08);
            color: #fff8ef;
        }

        body.app-body .topnav .global-search-form .search-input input::placeholder {
            color: rgba(236, 226, 214, 0.52);
        }

        body.app-body .player-toolbar,
        body.app-body .chart-panel,
        body.app-body .control-card {
            color: var(--ink);
        }

        body.app-body .control-label,
        body.app-body .control-value,
        body.app-body .tempo-readout,
        body.app-body .chart-section h2,
        body.app-body .chart-lines,
        body.app-body .chart-empty-state,
        body.app-body .measure,
        body.app-body .chord {
            color: var(--ink);
        }

        body.app-body .player-toolbar .beat-pill,
        body.app-body .player-toolbar .beat-pill small {
            color: var(--muted);
        }

        body.app-body .player-toolbar .beat-pill small {
            opacity: 1;
        }

        body.app-body .player-toolbar .button.subtle,
        body.app-body .player-toolbar .button.ghost {
            background: rgba(255, 255, 255, 0.92);
            border-color: rgba(15, 93, 80, 0.12);
            color: var(--accent-strong);
            box-shadow: none;
        }

        body.app-body .player-toolbar .button.subtle:hover,
        body.app-body .player-toolbar .button.ghost:hover {
            background: rgba(255, 255, 255, 0.98);
        }

        body.app-body .player-toolbar .button.subtle:focus-visible,
        body.app-body .player-toolbar .button.ghost:focus-visible {
            outline: 2px solid rgba(255, 107, 43, 0.28);
            outline-offset: 2px;
        }

        .mobile-tabbar {
            display: none;
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
            padding: 24px 28px;
            background:
                linear-gradient(180deg, rgba(255, 253, 249, 0.95), rgba(248, 243, 235, 0.88));
        }

        .library-hero::after {
            width: 180px;
            height: 180px;
            right: -40px;
            top: -70px;
            background: radial-gradient(circle, rgba(15, 93, 80, 0.08), transparent 72%);
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
            font-family: var(--font-body);
            font-weight: 800;
        }

        .player-head h1 {
            max-width: 12ch;
            font-size: clamp(1.9rem, 3.2vw, 2.9rem);
            line-height: 0.94;
        }

        .section-head h2,
        .feature-card h2,
        .song-card h2,
        .empty-card h2,
        .studio-side h2 {
            margin: 0;
            font-size: 1.42rem;
            line-height: 1.08;
            font-family: var(--font-body);
            font-weight: 800;
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

        .player-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .player-meta span {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            padding: 0 12px;
            border-radius: 999px;
            background: rgba(15, 93, 80, 0.07);
            color: var(--muted);
            font-size: 0.92rem;
            font-weight: 600;
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
            background: rgba(255, 255, 255, 0.72);
            color: var(--accent-strong);
            border: 1px solid rgba(15, 93, 80, 0.12);
            box-shadow: none;
        }

        .button.subtle {
            background: rgba(246, 240, 230, 0.86);
            color: var(--muted);
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

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .stats-grid,
        .song-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 320px), 1fr));
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
            font-family: var(--font-body);
            font-weight: 800;
        }

        .section-block {
            display: grid;
            gap: 16px;
            background:
                linear-gradient(180deg, rgba(255, 253, 249, 0.96), rgba(246, 240, 231, 0.92));
            border: 1px solid rgba(76, 58, 36, 0.08);
        }

        .section-head {
            padding-bottom: 6px;
            border-bottom: 1px solid rgba(76, 58, 36, 0.08);
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .global-search-form {
            flex: 1 1 240px;
            max-width: 310px;
        }

        .search-input {
            display: block;
            min-width: min(100%, 340px);
        }

        .search-input input {
            min-height: 48px;
            border-radius: 999px;
            padding-inline: 18px;
            background: rgba(255, 255, 255, 0.9);
        }

        .global-search-form .search-input,
        .global-search-form .search-input input {
            min-width: 0;
            width: 100%;
        }

        .topnav .global-search-form {
            margin-right: 10px;
        }

        .song-card {
            display: grid;
            grid-template-rows: auto auto auto 1fr auto;
            align-content: start;
            gap: 12px;
            position: relative;
            overflow: hidden;
            min-height: 190px;
            background:
                linear-gradient(180deg, rgba(255, 252, 248, 0.96), rgba(246, 239, 229, 0.92));
        }

        .song-card h2,
        .empty-card h2,
        .stat-card strong {
            text-wrap: balance;
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

        .song-card h2 {
            min-height: 2.3em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .song-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .song-tags span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 11px;
            border-radius: 999px;
            background: rgba(15, 93, 80, 0.08);
            color: var(--accent-strong);
            font-size: 0.78rem;
            font-weight: 700;
            line-height: 1;
        }

        .song-card .inline-actions {
            display: grid;
            grid-template-columns: repeat(3, minmax(88px, 1fr));
            gap: 10px;
            align-items: stretch;
        }

        .song-card .inline-actions form {
            margin: 0;
        }

        .song-card .inline-actions .button,
        .song-card .inline-actions form .button {
            width: 100%;
            min-height: 46px;
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

        .empty-card .button {
            width: auto;
            min-width: 180px;
            align-self: flex-start;
        }

            .studio-shell {
                display: grid;
                grid-template-columns: minmax(0, 1.18fr) minmax(340px, 0.82fr);
                gap: 28px;
                align-items: start;
            }

            .studio-shell-single {
                grid-template-columns: minmax(0, 1120px);
                justify-content: center;
            }

            .studio-main {
                display: grid;
                gap: 22px;
                padding: 22px;
                background:
                    linear-gradient(180deg, rgba(255, 252, 248, 0.96), rgba(246, 239, 229, 0.92));
            }

        .studio-main .button {
            min-width: 160px;
        }

        .studio-grid {
            display: grid;
            gap: 24px;
        }

        .live-set-builder {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(360px, 0.92fr);
            gap: 20px;
            align-items: start;
        }

        .live-set-browser,
        .live-set-order {
            display: grid;
            gap: 16px;
            min-width: 0;
        }

        .live-set-section-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .selection-count {
            display: inline-flex;
            align-items: center;
            min-height: 34px;
            padding: 0 12px;
            border-radius: 999px;
            background: rgba(17, 92, 79, 0.08);
            color: var(--accent-strong);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .live-song-list,
        .live-set-selection {
            display: grid;
            gap: 12px;
        }

        .live-song-option,
        .live-set-item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(138px, 154px);
            gap: 16px;
            align-items: center;
            padding: 18px;
            border-radius: 20px;
            border: 1px solid rgba(84, 65, 43, 0.08);
            background: rgba(255, 255, 255, 0.56);
        }

        .live-song-option > div {
            display: grid;
            gap: 10px;
            align-content: start;
            min-width: 0;
        }

        .live-set-item {
            grid-template-columns: auto minmax(0, 1fr) auto;
        }

        .live-song-option .button {
            width: 100%;
            min-width: 0;
            justify-content: center;
            align-self: stretch;
        }

        .live-set-item-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
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
            gap: 14px;
        }

        .player-toolbar,
        .chart-panel {
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            background: rgba(255, 255, 255, 0.88);
            padding: 10px;
        }

        .player-toolbar {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.3fr);
            gap: 8px;
            align-items: stretch;
        }

        .toolbar-group {
            display: grid;
            gap: 6px;
        }

        .control-card {
            padding: 10px 12px;
            border-radius: 14px;
            background: rgba(246, 240, 230, 0.8);
            border: 1px solid rgba(71, 55, 37, 0.08);
        }

        .control-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: var(--muted);
            font-weight: 700;
        }

        .control-cluster {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .control-button {
            min-width: 40px;
            min-height: 40px;
            padding: 0 14px;
        }

        .control-value,
        .tempo-readout {
            font-family: var(--font-body);
            font-size: 1.05rem;
            line-height: 1;
            letter-spacing: -0.04em;
            font-weight: 800;
        }

        .beat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0 10px;
            min-height: 40px;
            border-radius: 999px;
            background: rgba(15, 93, 80, 0.08);
            color: var(--accent-strong);
            font-weight: 700;
        }

        .beat-pill small {
            color: var(--muted);
            font-size: 0.68rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .beat-pill span {
            display: inline-grid;
            place-items: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent), var(--accent-strong));
            color: #fff;
            font-size: 0.92rem;
        }

        .chart-panel {
            display: grid;
            gap: 14px;
        }

        .live-set-preview-card {
            position: relative;
            overflow: hidden;
            padding: 22px;
            border-radius: 28px;
            border: 1px solid var(--line);
            background:
                radial-gradient(circle at top right, rgba(255, 142, 76, 0.14), transparent 28%),
                linear-gradient(145deg, rgba(9, 78, 67, 0.1), rgba(255, 252, 248, 0.96) 34%, rgba(246, 239, 229, 0.92));
        }

        .live-set-preview-card::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 6px;
            background: linear-gradient(180deg, #ff8d4d, #0f5a4d);
        }

        .live-set-preview-head {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .live-set-preview-list {
            display: grid;
            gap: 18px;
        }

        .live-set-player-list {
            gap: 24px;
        }

        .live-set-player-shell {
            gap: 22px;
        }

        .live-set-preview-copy {
            display: grid;
            gap: 6px;
            min-width: 0;
        }

        .live-set-order-chip {
            background: rgba(255, 141, 77, 0.18);
            color: #ffb181;
        }

        .button.is-static {
            pointer-events: none;
            user-select: none;
        }

        .live-set-toolbar-preview .button.is-static {
            opacity: 1;
        }

        .live-set-preview-copy .song-tags span:nth-child(1) {
            background: rgba(15, 90, 77, 0.12);
            color: var(--accent-strong);
        }

        .live-set-preview-copy .song-tags span:nth-child(2) {
            background: rgba(255, 141, 77, 0.14);
            color: #9d4d25;
        }

        .live-set-preview-copy .song-tags span:nth-child(3) {
            background: rgba(185, 133, 47, 0.16);
            color: #7a5a14;
        }

        .live-set-preview-card .button.ghost {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.88), rgba(255, 247, 238, 0.96));
            border-color: rgba(15, 90, 77, 0.16);
        }

        .chart-section {
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(71, 55, 37, 0.1);
        }

        .chart-empty-state {
            padding: 28px 0 8px;
            border-bottom: none;
            color: var(--muted);
        }

        .chart-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .live-set-preview-card .chart-panel {
            display: grid;
            gap: 12px;
        }

        .live-set-preview-card .chart-section {
            padding: 18px 18px 16px;
            border-radius: 22px;
            border: 1px solid rgba(84, 65, 43, 0.08);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.9), rgba(248, 243, 235, 0.88));
        }

        .live-set-preview-card .chart-section:nth-child(odd) {
            background:
                linear-gradient(180deg, rgba(255, 250, 244, 0.94), rgba(246, 236, 225, 0.9));
        }

        .live-set-preview-card .chart-section h2 {
            display: inline-flex;
            align-items: center;
            min-height: 34px;
            padding: 0 12px;
            border-radius: 999px;
            background: rgba(15, 90, 77, 0.1);
            color: var(--accent-strong);
            font-size: 0.92rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

            .chart-lines {
                display: grid;
                gap: 10px;
                white-space: pre-wrap;
                font-size: 0.98rem;
                line-height: 1.65;
            }

            .live-set-preview-card .chart-lines {
                margin-top: 14px;
            }

            .live-set-preview-card .chart-lines > div {
                color: var(--ink);
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
                min-width: 58px;
                padding: 7px 11px;
                border-radius: 12px;
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
            align-content: space-between;
        }

        .beat-indicator span {
            display: inline-grid;
            place-items: center;
            width: 38px;
            height: 38px;
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

                .live-set-builder {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 640px) {
                .live-song-option,
                .live-set-item {
                    grid-template-columns: 1fr;
                }

                .live-song-option .button,
                .live-set-item-actions {
                    width: 100%;
                }

                .live-set-item-actions {
                    justify-content: stretch;
                }

                .live-set-item-actions .button {
                    flex: 1 1 0;
                    min-width: 0;
                }
            }

            @media (max-width: 840px) {
                .landing-hero {
                    grid-template-columns: 1fr;
                    min-height: auto;
                    padding: 20px 0 8px;
                }

                .landing-copy {
                    max-width: 100%;
                }

                .landing-hero h1 {
                    max-width: 100%;
                    font-size: clamp(2.8rem, 12vw, 4.6rem);
                }

                .landing-visual {
                    min-height: 420px;
                }

                .landing-device-body {
                    min-height: 420px;
                }

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

                .topbar {
                    align-items: stretch;
                }

                .topnav {
                    width: 100%;
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 8px;
                }

                .topnav a,
                .topnav form {
                    width: 100%;
                    min-width: 0;
                }

                .topnav .button,
                .topnav a:not(.button) {
                    width: 100%;
                    justify-content: center;
                    white-space: nowrap;
                }

                .metadata-grid {
                    grid-template-columns: 1fr;
                }

                .search-form {
                    width: 100%;
                    flex-direction: column;
                    align-items: stretch;
                }

                .topnav .global-search-form {
                    grid-column: 1 / -1;
                    max-width: none;
                    margin-right: 0;
                }

                .search-input {
                    min-width: 100%;
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

                .library-hero-actions .button,
                .studio-actions .button,
                .danger-zone .button {
                    width: 100%;
                }

                .player-head h1 {
                    max-width: 100%;
                    font-size: clamp(2rem, 10vw, 3rem);
                    line-height: 0.96;
                }

                .player-head .inline-actions {
                    display: grid;
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 8px;
                }

                .player-head .inline-actions form {
                    margin: 0;
                }

                .player-head .inline-actions .button,
                .player-head .inline-actions form .button {
                    width: 100%;
                    min-height: 48px;
                }

                .player-toolbar {
                    display: grid;
                    grid-template-columns: 1fr;
                    gap: 10px;
                    padding: 12px;
                }

                .toolbar-group {
                    display: grid;
                    gap: 8px;
                }

                .control-card {
                    padding: 12px;
                    border-radius: 16px;
                }

                .control-cluster {
                    display: grid;
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 8px;
                }

                .control-cluster .button,
                .control-cluster strong {
                    width: 100%;
                    min-height: 46px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                }

                .control-value,
                .tempo-readout {
                    font-size: 1.2rem;
                }

                .beat-indicator {
                    margin-left: 0;
                }

                .beat-indicator .control-cluster {
                    grid-template-columns: 1fr;
                    justify-items: start;
                }

                .beat-indicator span {
                    width: 44px;
                    height: 44px;
                }

                .song-card {
                    min-height: 0;
                    gap: 10px;
                }

                .song-tags {
                    display: grid;
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 8px;
                }

                .song-tags span {
                    width: 100%;
                    justify-content: center;
                    text-align: center;
                    min-height: 38px;
                    padding: 0 8px;
                }

                .song-card .inline-actions {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 8px;
                }

                .song-card .inline-actions form {
                    margin: 0;
                }

                .song-card .inline-actions form .button {
                    width: 100%;
                }

                .player-meta {
                    gap: 6px;
                }

                .player-meta span {
                    min-height: 28px;
                    padding: 0 10px;
                    font-size: 0.86rem;
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
                .landing-hero {
                    gap: 20px;
                    padding-top: 6px;
                }

                .landing-hero h1 {
                    font-size: clamp(2.2rem, 13vw, 3.2rem);
                }

                .landing-lead {
                    font-size: 0.95rem;
                }

                .landing-actions {
                    display: grid;
                    grid-template-columns: 1fr;
                }

                .landing-stats {
                    gap: 8px;
                }

                .landing-stats span {
                    min-height: 34px;
                    padding: 0 12px;
                    font-size: 0.82rem;
                }

                .landing-visual {
                    min-height: 320px;
                }

                .landing-orb {
                    width: 92%;
                    right: 4%;
                    bottom: 8px;
                }

                .landing-device {
                    padding: 10px;
                    border-radius: 26px;
                }

                .landing-device-body {
                    min-height: 320px;
                    padding: 14px;
                    border-radius: 18px;
                }

                .landing-panel-primary,
                .landing-panel-secondary {
                    padding: 16px;
                    border-radius: 18px;
                }

                .landing-panel-primary strong,
                .landing-floating-card strong {
                    font-size: 1.14rem;
                }

                .landing-chip-row {
                    gap: 8px;
                }

                .landing-chip-row span {
                    min-height: 34px;
                    padding: 0 12px;
                    font-size: 0.82rem;
                }

                .landing-floating-card {
                    min-width: 126px;
                    padding: 10px 12px;
                }

                .landing-floating-right {
                    top: auto;
                    right: 14px;
                    bottom: 118px;
                }

                body::before {
                    background-size: 40px 40px;
                }

                .shell {
                    width: calc(100% - 16px);
                    padding: 10px 0 28px;
                }

                .content {
                    gap: 18px;
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
                    padding: 16px;
                }

                .topbar {
                    padding: 14px;
                    gap: 12px;
                }

                .hero h1,
                .page-head h1,
                .player-head h1,
                .library-hero h1 {
                    font-size: clamp(1.7rem, 9.8vw, 2.3rem);
                }

                .player-head {
                    gap: 10px;
                }

                .player-head h1 {
                    font-size: clamp(1.45rem, 7.8vw, 1.95rem);
                    line-height: 1;
                    letter-spacing: -0.05em;
                }

                .player-head p {
                    font-size: 0.9rem;
                }

                .section-head h2,
                .feature-card h2,
                .song-card h2,
                .empty-card h2 {
                    font-size: 1.35rem;
                }

                .search-input input {
                    min-height: 46px;
                }

                .button {
                    padding: 10px 14px;
                }

                .topnav {
                    grid-template-columns: 1fr 1fr;
                    gap: 6px;
                    align-items: stretch;
                }

                .topnav .button,
                .topnav a:not(.button) {
                    font-size: 0.9rem;
                    padding: 10px 12px;
                    min-height: 42px;
                    border-radius: 18px;
                    text-align: center;
                }

                .topnav a:not(.button) {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    background: rgba(255, 255, 255, 0.08);
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    color: rgba(255, 250, 243, 0.92);
                }

                .brand {
                    gap: 10px;
                    width: 100%;
                    align-items: center;
                }

                .brand-mark {
                    width: 42px;
                    height: 42px;
                    border-radius: 14px;
                }

                .brand-text strong {
                    font-size: 0.96rem;
                }

                .brand-text small {
                    font-size: 0.56rem;
                }

                .library-hero {
                    gap: 14px;
                }

                .section-head {
                    gap: 12px;
                    align-items: flex-start;
                }

                .section-head > div {
                    width: 100%;
                }

                .library-hero .section-lead {
                    margin-top: 8px;
                    font-size: 0.9rem;
                    line-height: 1.5;
                }

                .library-hero-actions .button {
                    min-height: 44px;
                }

                .song-card {
                    gap: 8px;
                    align-content: start;
                }

                .song-card h2 {
                    font-size: 1.05rem;
                    line-height: 1.12;
                    margin-top: 2px;
                }

                .song-meta {
                    font-size: 0.64rem;
                    letter-spacing: 0.14em;
                }

                .song-tags {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 6px;
                }

                .song-tags span {
                    min-height: 36px;
                    padding: 0 8px;
                    font-size: 0.72rem;
                    white-space: nowrap;
                    line-height: 1;
                }

                .song-card .inline-actions {
                    grid-template-columns: 1fr 1fr;
                    gap: 6px;
                    align-items: stretch;
                }

                .song-card .inline-actions .button,
                .song-card .inline-actions form .button {
                    min-height: 40px;
                    font-size: 0.88rem;
                    padding: 0 10px;
                }

                .song-card .inline-actions form {
                    grid-column: 1 / -1;
                }

                .player-head .inline-actions {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 6px;
                }

                .player-head .inline-actions .button,
                .player-head .inline-actions form .button {
                    min-height: 40px;
                    font-size: 0.92rem;
                    padding: 0 10px;
                }

                .player-toolbar {
                    padding: 8px;
                    gap: 8px;
                }

                .control-card {
                    padding: 8px 10px;
                    border-radius: 12px;
                }

                .control-label {
                    font-size: 0.64rem;
                    letter-spacing: 0.1em;
                }

                .control-cluster {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    align-items: center;
                }

                .control-cluster .button,
                .control-cluster strong {
                    min-height: 38px;
                    font-size: 0.9rem;
                    padding: 0 10px;
                }

                .tempo-readout,
                .control-value {
                    font-size: 0.88rem;
                }

                .control-button {
                    min-width: 36px;
                    min-height: 36px;
                    padding: 0 8px;
                }

                .beat-pill {
                    min-height: 36px;
                    padding: 0 8px;
                    gap: 6px;
                }

                .beat-pill small {
                    font-size: 0.58rem;
                    letter-spacing: 0.08em;
                }

                .beat-pill span {
                    width: 24px;
                    height: 24px;
                    font-size: 0.82rem;
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
<body class="{{ request()->routeIs('welcome') ? 'landing-body' : '' }} {{ auth()->check() && !request()->routeIs('welcome') ? 'app-body' : '' }}">
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
                    <form method="GET" action="{{ route('songs.index') }}" class="search-form global-search-form">
                        <label class="search-input">
                            <span class="sr-only">Search songs</span>
                            <input
                                type="search"
                                name="search"
                                value="{{ request('search', '') }}"
                                placeholder="Search songs"
                            >
                        </label>
                    </form>
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('songs.index') }}">Songs</a>
                    <a href="{{ route('live-sets.index') }}">Live Sets</a>
                    <a class="button ghost" href="{{ route('songs.create') }}">New Song</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="button danger-outline">Logout</button>
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

        @auth
            <nav class="mobile-tabbar" aria-label="Mobile navigation">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Dashboard</a>
                <a href="{{ route('songs.index') }}" class="{{ request()->routeIs('songs.index', 'songs.player') ? 'is-active' : '' }}">Songs</a>
                <a href="{{ route('live-sets.index') }}" class="{{ request()->routeIs('live-sets.*') ? 'is-active' : '' }}">Sets</a>
            </nav>
        @endauth
    </div>
</body>
</html>
