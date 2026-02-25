@extends('layouts.app')

@section('title', 'Create Story')

@section('content')
    <div class="container">
        <div class="mt-5">
            <form action="{{ route('story.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    {{-- Stories allow exactly 1 category (radio buttons enforce single selection) --}}
                    <label class="form-label"><span class="fw-bold">Category</span> (select 1)</label>
                    <br>
                    @foreach($all_categories as $category)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="{{ $category->id }}" id="cat_{{ $category->id }}" name="category[]">
                            <label class="form-check-label" for="cat_{{ $category->id }}">
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
                    <label for="image" class="fw-bold">Image</label>
                    <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info">
                    <div class="form-text" id="image-info">
                        The acceptable formats are jpeg, jpg, png, and gif only.<br>
                        Max file size is 1048kB. Stories expire after 24 hours for other users.
                    </div>
                    {{-- Error --}}
                    @error('image')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary px-5">Post Story</button>
            </form>
        </div>
    </div>
@endsection
