@extends('layouts.app')

@section('content')

    <body>
        <div class="content">
            <h2>{{ $post['title'] }}</h2>
            @foreach ($post->images as $image)
                <img src="{{ $image['url'] }}" alt="{{ $post->title }}" style="object-fit: cover;" width="240"
                    height="240">
            @endforeach
        </div>
    </body>

@endsection
