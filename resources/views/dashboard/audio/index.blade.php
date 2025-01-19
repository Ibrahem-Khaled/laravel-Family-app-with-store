@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Audio Files</h1>
        <!-- Button to trigger the Create Audio Modal -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createAudioModal">
            Create New Audio
        </button>

        @include('components.alerts')

        <!-- Audio Files Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>File</th>
                    <th>Duration</th>
                    <th>Sub-Category</th>
                    <th>User</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($audioFiles as $audio)
                    <tr>
                        <td>{{ $audio->id }}</td>
                        <td>{{ $audio->title }}</td>
                        <td>
                            <!-- مشغل الصوت -->
                            <audio controls>
                                <source src="{{ Storage::url($audio->file) }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </td>
                        <td>{{ $audio->duration }}</td>
                        <td>{{ $audio->subCategory->name }}</td>
                        <td>{{ $audio->user->name }}</td>
                        <td>
                            <!-- Button to trigger the Edit Audio Modal -->
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editAudioModal{{ $audio->id }}">
                                Edit
                            </button>
                            <!-- Button to trigger the Delete Audio Modal -->
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteAudioModal{{ $audio->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Audio Modal -->
                    <div class="modal fade" id="editAudioModal{{ $audio->id }}" tabindex="-1"
                        aria-labelledby="editAudioModalLabel{{ $audio->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editAudioModalLabel{{ $audio->id }}">Edit Audio</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('audio.update', $audio->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="sub_category_id">Sub-Category</label>
                                            <select name="sub_category_id" class="form-control" required>
                                                @foreach ($subCategories as $subCategory)
                                                    <option value="{{ $subCategory->id }}"
                                                        {{ $audio->sub_category_id == $subCategory->id ? 'selected' : '' }}>
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
                                                        {{ $audio->user_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $audio->title }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="file">File</label>
                                            <input type="file" name="file" class="form-control">
                                            <small>Current file: <a href="{{ Storage::url($audio->file) }}"
                                                    target="_blank">{{ basename($audio->file) }}</a></small>
                                        </div>
                                        <div class="form-group">
                                            <label for="duration">Duration</label>
                                            <input type="text" name="duration" class="form-control"
                                                value="{{ $audio->duration }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" class="form-control">{{ $audio->description }}</textarea>
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

                    <!-- Delete Audio Modal -->
                    <div class="modal fade" id="deleteAudioModal{{ $audio->id }}" tabindex="-1"
                        aria-labelledby="deleteAudioModalLabel{{ $audio->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteAudioModalLabel{{ $audio->id }}">Delete Audio</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('audio.destroy', $audio->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-body">
                                        Are you sure you want to delete this audio file?
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

    <!-- Create Audio Modal -->
    <div class="modal fade" id="createAudioModal" tabindex="-1" aria-labelledby="createAudioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAudioModalLabel">Create New Audio</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('audio.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="file">File</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="duration">Duration</label>
                            <input type="text" name="duration" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control"></textarea>
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