@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label class="form-label"><span class="fw-bold">Category</span> (up to 3)</label>
            <br>
            @foreach($all_categories as $category)
                <div class="form-check form-check-inline">
                    @if (in_array($category->id,$selected_categories))
                        <input class="form-check-input" type="checkbox" value="{{ $category->id }}" id="{{ $category->name }}" name="category[]" {{ in_array($category->id,$selected_categories) ? 'checked' : '' }} checked>
                    @else
                        <input class="form-check-input" type="checkbox" value="{{ $category->id }}" id="{{ $category->name }}" name="category[]" {{ in_array($category->id,$selected_categories) ? 'checked' : '' }}>
                    @endif
                    <label class="form-check-label" for="{{ $category->name }}">
                        {{ $category->name }}
                    </label>
                </div>
            @endforeach
            {{-- Error --}}
            @error('category')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea class="form-control" id="description" name="description" cols="4" rows="3" placeholder="What's on your mind?">{{ old('description', $post->description) }}</textarea>
            {{-- Error --}}
            @error('description')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <label for="image fw-bold">Image</label>
                <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="img-thumbnail w-100">
                <input type="file" name="image" id="image" class="form-control mt-1" aria-describedby="image-info">
                <div class="form-text" id="image-info">
                    The acceptable formats are jpeg, jpg, png,and gif only.<br>
                    Max file size is 1048kB.
                </div>
                {{-- Error --}}
                @error('image')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <button type="submit" class="btn btn-warning px-5">Save</button>
    </form>
@endsection