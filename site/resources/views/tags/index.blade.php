@extends('layouts.app')


@section('content')
    <h1>Tag</h1>
    <strong class="d-inline-block mb-2 text-primary">
        @foreach ($tags as $tag)
            <a href="/tags/{{ $tag['tag_id'] }}">
                <span class="badge rounded-pill bg-light text-dark">
                    #{{ $tag['label'] }}</a>
            </span>
        @endforeach
    </strong>
@endsection
