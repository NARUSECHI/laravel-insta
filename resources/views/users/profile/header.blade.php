<div class="row">
    <div class="col-4">
        @if ($user->avatar)
            <img src="{{ asset('storage/avatars/'.$user->avatar) }}" alt="{{$user->avatar}}" class="img-thumnbail rounded-circle d-block mx-auto avatar-lg">
        @else
            <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>
        @endif
    </div>
    <div class="col-8">
        <div class="row mb-3">
            <div class="col-auto">
                <h2 class="display-6 mb-0">{{$user->name}}</h2>
            </div>
            <div class="col-auto p-2">
                @if (Auth::user()->id === $user->id)
                    <a href="{{route('profile.edit')}}" class="btn btn-outline-secondary btn-sm fw-bold">Edit Profile</a>                    
                @else
                    @if ($user->isFollowed())
                        <form action="{{route('follow.destroy',$user->id)}}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary btn-sm fw-bold">Followng</button>
                        </form>
                    @else
                        <form action="{{ route('follow.store',$user->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                        </form> 
                    @endif
                                       
                @endif
            </div>
            <div class="row mb-3">
                <div class="col-auto">
                    <a href="{{route('profile.show',$user->id)}}" class="text-decoration-none text-dark">
                        <strong>{{$user->posts->count()}}</strong> posts
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('profile.followers',$user->id )}}" class="text-decoration-none text-dark">
                        <strong>{{ $user->followers->count() }}</strong> followers
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('profile.following',$user->id) }}" class="text-decoration-none text-dark">
                        <strong>{{ $user->following->count() }}</strong> following
                    </a>
                </div>
            </div>
            <p class="fw-bold">{{$user->introduction}}</p>
        </div>
    </div>
</div>