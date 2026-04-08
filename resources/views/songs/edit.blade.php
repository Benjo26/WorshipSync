@extends('layouts.app')

@section('content')
    <section class="page-head">
        <div>
            <p class="eyebrow">Edit Song</p>
            <h1>{{ $song->title }}</h1>
        </div>
    </section>

    <form method="POST" action="{{ route('songs.update', $song) }}" class="panel">
        @csrf
        @method('PUT')
        @include('songs._form', ['song' => $song, 'chart' => $chart])
        <div class="inline-actions">
            <button class="button" type="submit">Update Song</button>
            <a class="button ghost" href="{{ route('songs.player', $song) }}">Cancel</a>
        </div>
    </form>

    <form method="POST" action="{{ route('songs.destroy', $song) }}" class="danger-zone">
        @csrf
        @method('DELETE')
        <button class="button danger" type="submit">Delete Song</button>
    </form>
@endsection
