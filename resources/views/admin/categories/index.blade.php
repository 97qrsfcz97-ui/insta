@extends('layouts.app')

@section('title', 'Admin: Categories')

@section('content')
    {{-- Add Category Form --}}
    <form action="{{ route('admin.categories.store') }}" method="post" class="mb-3">
        @csrf
        <div class="d-flex gap-2">
            <input type="text" name="name" class="form-control" placeholder="Add a category" value="{{ old('name') }}">
            <button type="submit" class="btn btn-primary text-nowrap">+ Add</button>
        </div>
        @error('name')
            <p class="text-danger small mt-1">{{ $message }}</p>
        @enderror
    </form>

    {{-- Categories Table --}}
    <table class="table table-hover align-middle bg-white border text-secondary">
        <thead class="small table-warning text-secondary">
            <tr>
                <th>#</th>
                <th>NAME</th>
                <th>COUNT</th>
                <th>LAST UPDATED</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->category_post_count }}</td>
                    <td>{{ $category->updated_at }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-category-{{ $category->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-category-{{ $category->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                        {{-- include modals here --}}
                        @include('admin.categories.modals.edit')
                        @include('admin.categories.modals.delete')
                    </td>
                </tr>
            @endforeach

            {{-- Uncategorized Row --}}
            <tr>
                <td></td>
                <td>
                    Uncategorized
                    <br>
                    <span class="small text-muted">Hidden posts are not included.</span>
                </td>
                <td>{{ $uncategorized_count }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
