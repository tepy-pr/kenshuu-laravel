@extends('layouts.app')

@section('content')
    @can('update', $post)
        <form method="POST" action="/posts/{{ $post->post_id }}" enctype="multipart/form-data">
            @csrf
            @method("PUT")

            @isset($errorMsg)
                <div class="alert alert-danger">{{ $errorMsg }}</div>
            @endisset
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $post->title }}"
                    placeholder="Title Here...">
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Content</label>
                <textarea class="form-control" name="body" id="body" rows="10">{{ $post->body }}</textarea>
            </div>

            <button type="submit" name="uploadImage" class="btn btn-primary">Publish</button>
        </form>
    @endcan
    @cannot('update', $post)
        <p>{{ $errorMsg }}</p>
    @endcannot
@endsection
