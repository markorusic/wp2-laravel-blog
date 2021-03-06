@php
    $isUserPost = $post->user_id === auth()->id();
@endphp
@extends('public.shared.layout')

@section('content')
    <div class="px-5 my-4">
        <div class="d-flex justify-content-between">
            <h1>{{ $post->title }}</h1>
            @if ($isUserPost)
                <div class="d-flex">
                    <div class="mr-2">
                        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">
                            <i class="fa fa-pencil mr-2"></i>
                            {{ __('Edit post') }}
                        </a>
                    </div>
                    <div>
                        <a id="delete-post"
                            class="btn btn-danger"
                            href="{{ route('posts.destroy', ['post' => $post->id]) }}"
                        >
                            <i class="fa fa-trash mr-2"></i>
                            {{ __('Remove post') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <h5 class="text-secondary font-weight-normal mt-3 mb-5">{{ $post->description }}</h5>
        <div class="d-flex justify-content-between mb-4">
            <div class="d-flex">
                <div class="mr-3">
                    @include('public.user.user-avatar', [
                        'user' => $post->user
                    ])
                </div>
                <div class="d-flex flex-column">
                    @include('public.user.user-name', [
                        'user' => $post->user
                    ])
                    <span class="text-secondary">{{ $post->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            @include('public.user.follow-button', [
                'user' => $post->user
            ])
        </div>

        <div class="d-flex justify-content-center mb-5">
            <img 
                class="img-fluid"
                src="{{ $post->main_photo }}"
                alt="{{ $post->title }}"
            >
        </div>

        <div id="content" class="mb-5"></div>
        <textarea id="content-ta" class="d-none">{{ $post->content }}</textarea>

        @include('public.category.tag-category-list', [
            'categories' => $post->categories
        ])

        <div class="d-flex justify-content-between mt-5 mb-4">
            <div class="mr-4 d-flex align-items-baseline">
                <a id="like-action" href="#" class="text-dark">
                    <i class="fa fa-thumbs-{{ $post->isLiked() ? '' : 'o-' }}up fa-2x mr-2"></i>
                </a>
                <a href="#"
                    class="text-dark fs-25"
                    data-likes-count
                    data-toggle="modal"
                    data-target="#like-users-modal"
                >{{ $post->likes_count }}</a>
                @include('shared.modal', [
                    'id' => 'like-users-modal',
                    'title' => 'Likes'
                ])
            </div>
            <form id="comment-form" class="w-100">
                <div class="input-group mb-3">
                    <input type="text" name="content" required class="form-control" placeholder="Write a comment..." aria-label="Write a comment...">
                </div>
            </form>
        </div>

        <h3 id="comment-list-title" class="mb-5 pb-3 border-bottom"></h3>
        <div id="comment-list"></div>
    </div>
@endsection
