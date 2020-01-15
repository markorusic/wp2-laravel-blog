@extends('public.shared.layout')

@section('content')
    <div class="my-3">
        @include('public.post.post-list', [
            'title' => 'Popular posts',
            'posts' => $posts
        ])
    </div>
@endsection
