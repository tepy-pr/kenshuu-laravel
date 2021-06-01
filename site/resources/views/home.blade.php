@extends('layouts.app')

@section('content')
    @foreach ($posts as $post)
        <p>{{ $post['title'] }}</p>
        <img src="{{ $post['thumbnail'] }}" alt="" width="240" height="240">
    @endforeach

@endsection
