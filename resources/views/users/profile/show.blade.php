@extends('layouts.app')

@section('title','Profile')

@section('content')
    @include('users.profile.header')

    {{-- Show all posts here --}}
    <div style="margin-top:100px">
        @if ($user->posts->isNotEmpty())
            <div class="row">
                @foreach ($user->posts as $post)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{route('post.show',$post->id)}}">
                            <img src="{{asset('storage/images/'.$post->image)}}" alt="$post->image" class="grid-img">

                        </a>
                    </div>
                @endforeach
            </div>
            <div class="col-auto">
                <a href="{{ route('profile.show',$user->id)}}" class="text-decoration-none text-dark"><strong>{{$user->posts->count()}}</strong>
                    {{$user->posts->count() == 1 ? 'post':'posts'}}
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route('profile.followers',$user->id) }}" class="text-decoration-none text-dark"><strong>{{ $user->followers->count() }}</strong>
                    {{ $user->followers->count() == 1 ? 'follower':'followers'}}
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route('profile.following',$user->id) }}" class="text-decoration-none text-dark"><strong>{{ $user->followers->count() }}</strong> following</a>
            </div>
        @else
            <h3 class="text-muted text-center">No posts yet</h3>
        @endif
    </div>
@endsection