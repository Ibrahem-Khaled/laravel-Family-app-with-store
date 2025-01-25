@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Users</h1>
        <!-- Button to trigger the Create User Modal -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createUserModal">
            Create New User
        </button>

        @include('components.alerts')

        <!-- Users Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Avatar</th>
                    <th>Address</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            @if ($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" width="50">
                            @else
                                No Avatar
                            @endif
                        </td>
                        <td>{{ $user->address }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <!-- Button to trigger the Edit User Modal -->
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editUserModal{{ $user->id }}">
                                Edit
                            </button>
                            <!-- Button to trigger the Delete User Modal -->
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteUserModal{{ $user->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Edit User Modal -->
                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
                        aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('users.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $user->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" name="username" class="form-control"
                                                value="{{ $user->username }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $user->email }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ $user->phone }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="avatar">upload avatar</label>
                                            <input type="file" name="avatar" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" name="address" class="form-control"
                                                value="{{ $user->address }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password (leave blank to keep current)</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select name="role" class="form-control" required>

                                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User
                                                </option>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin
                                                </option>
                                                <option value="creator" {{ $user->role == 'creator' ? 'selected' : '' }}>
                                                    Creator</option>
                                               <option value="family" {{ $user->role == 'family' ? 'selected' : '' }}>
                                                    Family</option> 
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

                    <!-- Delete User Modal -->
                    <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1"
                        aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">Delete User</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-body">
                                        Are you sure you want to delete this user?
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

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="avatar">Avatar URL</label>
                            <input type="text" name="avatar" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" class="form-control" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="creator">Creator</option>
                                <option value="family">Family</option>
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
