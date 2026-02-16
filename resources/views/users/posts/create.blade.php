@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
    <div class="container">
        <div class="mt-5">
            <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label"><span class="fw-bold">Category</span> (up to 3)</label>
                    <br>
                    @foreach($all_categories as $category)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="{{ $category->id }}" id="{{ $category->name }}" name="category[]">
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
                    <textarea class="form-control" id="description" name="description" cols="4" rows="3" placeholder="What's on your mind?">{{ old('description') }}</textarea>
                    {{-- Error --}}
                    @error('description')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image fw-bold">Image</label>
                    <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info">
                    <div class="form-text" id="image-info">
                        The acceptable formats are jpeg, jpg, png,and gif only.<br>
                        Max file size is 1048kB.
                    </div>
                    {{-- Error --}}
                    @error('image')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary px-5">Post</button>
            </form>
        </div>
    </div>
@endsection