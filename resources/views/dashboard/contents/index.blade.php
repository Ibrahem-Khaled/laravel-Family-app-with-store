@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Contents</h1>
        <!-- Button to trigger the Create Content Modal -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createContentModal">
            Create New Content
        </button>

        @include('components.alerts')

        <!-- Contents Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Sub-Category</th>
                    <th>User</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contents as $content)
                    <tr>
                        <td>{{ $content->id }}</td>
                        <td>{{ $content->title }}</td>
                        <td>{{ $content->type }}</td>
                        <td>{{ $content->subCategory->name }}</td>
                        <td>{{ $content->user->name }}</td>
                        <td>
                            @if ($content->images)
                                @foreach (json_decode($content->images) as $image)
                                    <img src="{{ Storage::url($image) }}" alt="Image" width="50" class="mr-2">
                                @endforeach
                            @else
                                No Images
                            @endif
                        </td>
                        <td>
                            <!-- Button to trigger the Edit Content Modal -->
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editContentModal{{ $content->id }}">
                                Edit
                            </button>
                            <!-- Button to trigger the Delete Content Modal -->
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteContentModal{{ $content->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Content Modal -->
                    <div class="modal fade" id="editContentModal{{ $content->id }}" tabindex="-1"
                        aria-labelledby="editContentModalLabel{{ $content->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editContentModalLabel{{ $content->id }}">Edit Content</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('contents.update', $content->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="sub_category_id">Sub-Category</label>
                                            <select name="sub_category_id" class="form-control" required>
                                                @foreach ($subCategories as $subCategory)
                                                    <option value="{{ $subCategory->id }}"
                                                        {{ $content->sub_category_id == $subCategory->id ? 'selected' : '' }}>
                                                        {{ $subCategory->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="user_id">User</label>
                                            <select name="user_id" class="form-control" required>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ $content->user_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select name="type" class="form-control" required>
                                                <option value="article"
                                                    {{ $content->type == 'article' ? 'selected' : '' }}>Article</option>
                                                <option value="product"
                                                    {{ $content->type == 'product' ? 'selected' : '' }}>Product</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $content->title }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" class="form-control">{{ $content->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="number" name="price" class="form-control"
                                                value="{{ $content->price }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="colors">Colors (JSON)</label>
                                            <input type="text" name="colors" class="form-control"
                                                value="{{ json_encode($content->colors) }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="sizes">Sizes (JSON)</label>
                                            <input type="text" name="sizes" class="form-control"
                                                value="{{ json_encode($content->sizes) }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="images">Images</label>
                                            <input type="file" name="images[]" class="form-control" multiple>
                                            @if ($content->images)
                                                @foreach (json_decode($content->images) as $image)
                                                    <img src="{{ Storage::url($image) }}" alt="Image" width="50"
                                                        class="mr-2">
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity">Quantity</label>
                                            <input type="number" name="quantity" class="form-control"
                                                value="{{ $content->quantity }}">
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

                    <!-- Delete Content Modal -->
                    <div class="modal fade" id="deleteContentModal{{ $content->id }}" tabindex="-1"
                        aria-labelledby="deleteContentModalLabel{{ $content->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteContentModalLabel{{ $content->id }}">Delete
                                        Content</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('contents.destroy', $content->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-body">
                                        Are you sure you want to delete this content?
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

    <!-- Create Content Modal -->
    <div class="modal fade" id="createContentModal" tabindex="-1" aria-labelledby="createContentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createContentModalLabel">Create New Content</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="sub_category_id">Sub-Category</label>
                            <select name="sub_category_id" class="form-control" required>
                                @foreach ($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user_id">User</label>
                            <select name="user_id" class="form-control" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="type" class="form-control" required>
                                <option value="article">Article</option>
                                <option value="product">Product</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" name="price" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="colors">Colors (JSON)</label>
                            <input type="text" name="colors" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="sizes">Sizes (JSON)</label>
                            <input type="text" name="sizes" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="images">Images</label>
                            <input type="file" name="images[]" class="form-control" multiple>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" class="form-control">
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
