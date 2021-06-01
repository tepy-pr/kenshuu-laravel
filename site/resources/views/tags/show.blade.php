@extends('layouts.app')


@section('content')
    @foreach ($posts as $post)
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="card flex-md-row mb-4 shadow-sm h-md-250">
                    <div class="card-body d-flex flex-column align-items-start">
                        <strong class="d-inline-block mb-2 text-primary">
                            @foreach ($post->tags->toArray() as $tag)
                                <a href="/tags/{{ $tag['tag_id'] }}">
                                    <span class="badge rounded-pill bg-light text-dark">
                                        #{{ $tag['label'] }}</span></a>
                            @endforeach
                        </strong>
                        <h3 class="mb-0">
                            <a class="text-dark" href="/posts/{{ $post['post_id'] }}">{{ $post['title'] }}</a>
                        </h3>
                        <div class="card-text mb-auto text-truncate d-inline-block" style="max-width: 250px;">
                            {{ $post['body'] }}
                        </div>
                        <a href="/posts/{{ $post['post_id'] }}">Continue reading</a>
                    </div>
                    <img class="card-img-right flex-auto d-none d-lg-block" src="{{ $post['thumbnail'] }}"
                        style="object-fit: cover; height: 200px; width: 200px;" alt="{{ $post['title'] }}" />
                </div>
            </div>
        </div>
    @endforeach
@endsection
