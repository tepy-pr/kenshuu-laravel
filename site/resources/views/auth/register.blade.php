@extends('layouts.app')

@section('content')


    <h1>Sign Up</h1>
    <form action="/register" method="post">
        @csrf
        <div class="mb-3">
            <label for="username">Username</label>
            <input type="text" id="username" class="form-control @error('username') is-invalid @enderror" name="username">

            @error('username')
                <div class="alert alert-danger">Error</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" id="password" class="form-control" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>

@endsection
