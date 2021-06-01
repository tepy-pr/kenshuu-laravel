@extends('layouts.app')

@section('content')


    <h1>Log In</h1>
    <form action="/login" method="post">
        @csrf
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" id="password" class="form-control" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Log In</button>
    </form>

@endsection
