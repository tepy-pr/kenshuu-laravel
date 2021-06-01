@extends('layouts.app')


@section('content')
    <form method="POST" action="/posts" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">

            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                placeholder="Title Here...">

            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div>
                <input type="file" name="postImages[][image]" multiple />
            </div>
            <label for="tags" class="form-label">Tags (Please use "," to separate tags)</label>
            <input type="text" name="tags" class="form-control" id="tags" placeholder="Tags Here...">
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Content</label>
            <textarea class="form-control" name="body" id="body" rows="10"></textarea>
        </div>

        <button type="submit" name="uploadImage" class="btn btn-primary">Publish</button>

    </form>
@endsection
