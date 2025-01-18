@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Sub-Categories</h1>
        <!-- Button to trigger the Create Sub-Category Modal -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createSubCategoryModal">
            Create New Sub-Category
        </button>

        @include('components.alerts')

        <!-- Sub-Categories Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subCategories as $subCategory)
                    <tr>
                        <td>{{ $subCategory->id }}</td>
                        <td>{{ $subCategory->name }}</td>
                        <td>{{ $subCategory->slug }}</td>
                        <td>{{ $subCategory->image }}</td>
                        <td>{{ $subCategory->category->name }}</td>
                        <td>
                            <!-- Button to trigger the Edit Sub-Category Modal -->
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editSubCategoryModal{{ $subCategory->id }}">
                                Edit
                            </button>
                            <!-- Button to trigger the Delete Sub-Category Modal -->
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteSubCategoryModal{{ $subCategory->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Sub-Category Modal -->
                    <div class="modal fade" id="editSubCategoryModal{{ $subCategory->id }}" tabindex="-1"
                        aria-labelledby="editSubCategoryModalLabel{{ $subCategory->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSubCategoryModalLabel{{ $subCategory->id }}">Edit
                                        Sub-Category</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('sub-categories.update', $subCategory->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $subCategory->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="slug">Slug</label>
                                            <input type="text" name="slug" class="form-control"
                                                value="{{ $subCategory->slug }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Image URL</label>
                                            <input type="text" name="image" class="form-control"
                                                value="{{ $subCategory->image }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="category_id">Category</label>
                                            <select name="category_id" class="form-control" required>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $subCategory->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Sub-Category Modal -->
                    <div class="modal fade" id="deleteSubCategoryModal{{ $subCategory->id }}" tabindex="-1"
                        aria-labelledby="deleteSubCategoryModalLabel{{ $subCategory->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteSubCategoryModalLabel{{ $subCategory->id }}">Delete
                                        Sub-Category</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('sub-categories.destroy', $subCategory->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-body">
                                        Are you sure you want to delete this sub-category?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Sub-Category Modal -->
    <div class="modal fade" id="createSubCategoryModal" tabindex="-1" aria-labelledby="createSubCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubCategoryModalLabel">Create New Sub-Category</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sub-categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="image">Image URL</label>
                            <input type="text" name="image" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select name="category_id" class="form-control" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
