@extends('layouts.app')

@section('title','Admin: Posts')

@section('content')
    <table class="table table-hover align-middle bg-white border text-secondary">
        <thead class="table-primary text-secondary small">
            <tr>
                <th></th>
                <th></th>
                <th>CATEGORY</th>
                <th>OWNER</th>
                <th>CREATED</th>
                <th>STATUS</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse ($all_posts as $post)
            <tr>
                <td class="text-end">{{ $post->id }}</td>    
                <td>
                    <a href="{{ route('post.show',$post->id) }}">
                        <img src="{{ asset('storage/images/'.$post->image) }}" alt="{{ $post->image }}" class="d-bloeck mx-auto image-lg">
                    </a>
                </td>
                <td>
                    @foreach ($post->categoryPost as $category_post)
                        <span class="badge bg-secondary bg-opacity-50">{{ $category_post->category->name }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('profile.show',$post->user->id) }}" class="text-dark text-decoration-none">{{ $post->created_at }}</a>    
                </td>
                <td>
                    {{ $post->created_at }}    
                </td>
                <td>
                    @if ($post->trashed())
                        <i class="fa-solid fa-circle-minus text-secondary"></i>&nbsp; Hidden
                    @else
                        <i class="fa-solid fa-circle text-primary"></i>&nbsp; Visible
                    @endif
                </td>  
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-ellipsis"></i>
                        </button>

                    @if ($post->trashed())
                    <div class="dropdown-menu">
                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#unhide-post-{{ $post->id }}">
                            <i class="fa-solid fa-eye"></i> Unhide Post {{ $post->id }}
                        </button>
                    </div>                        
                    @else
                        <div class="dropdown-menu">
                            <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#hide-post-{{ $post->id }}">
                                <i class="fa-solid fa-eye-slash"></i> Hide Post {{ $post->id }}
                            </button>
                        </div>
                    @endif
                </div>
                    {{-- Include the modal here --}}
                    @include('admin.posts.modal.status')
                </td>
            </tr>            
        @empty
            <tr>
                <td colspan="7" class="lead text-muted text-center">No Posts Found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $all_posts->links() }}
@endsection