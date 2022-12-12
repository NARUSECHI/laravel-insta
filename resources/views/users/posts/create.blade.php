@extends('layouts.app')

@section('title','Create Post')

@section('content')
    <form action=" {{ route('post.store')}}" method="post" enctype="multipart/form-data">
        {{-- cross side reqest forgeries --}}
        @csrf

        <div class="mb-3">
            <lavel class="form-label d-block fw-bold">
                Category <span class="text-muted fw-normal">(up to 3)</span>
            </lavel>

            @foreach ($all_categories as $category)
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="category[]" id="{{ $category->name}}" value="{{ $category->id }}" class="form-check-input">
                    <label for="{{$category->name}}" class="form-check-label">{{$category->name}}</label>
                </div>   
            @endforeach
            @error('category')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control" Placeholder="What's on your mind">{{old('description')}}</textarea>
            @error('description')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="image" class="form-label fw-bold">Image</label>
            <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info">
            <div class="form-text" id="image-info">
                The acceptable formats are jpeg, jpg, png, and GIF only. <br>
                Max File size is 1048kb.
            </div>
            @error('image')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        

        <button type="submit" class="btn btn-primary px-5">Post</button>

    </form>
    
@endsection