@extends('layouts.app')

@section('content')
    <section class="page-head">
        <div>
            <p class="eyebrow">New Song</p>
            <h1>Create Worship Song</h1>
        </div>
    </section>

    <form method="POST" action="{{ route('songs.store') }}" class="panel">
        @csrf
        @include('songs._form')
        <button class="button" type="submit">Save Song</button>
    </form>
@endsection
