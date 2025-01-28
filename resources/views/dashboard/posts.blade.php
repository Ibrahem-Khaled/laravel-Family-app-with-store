@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>قائمة المنشورات</h1>

        <!-- زر لفتح Modal للإضافة -->
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
            إنشاء منشور جديد
        </button>

        @include('components.alerts')

        <!-- جدول لعرض البيانات -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>الوصف</th>
                    <th>الصور</th>
                    <th>الفيديوهات</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->description }}</td>
                        <td>
                            @foreach (json_decode($post->images, true) ?? [] as $image)
                                <img src="{{ $image }}" alt="صورة" width="100">
                            @endforeach
                        </td>
                        <td>
                            @foreach (json_decode($post->videos, true) ?? [] as $video)
                                <video width="100" controls>
                                    <source src="{{ $video }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endforeach
                        </td>
                        <td>{{ $post->status }}</td>
                        <td>
                            <!-- زر التعديل -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editModal{{ $post->id }}">
                                تعديل
                            </button>
                            <!-- زر الحذف -->
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal للتعديل لكل سجل -->
                    <div class="modal fade" id="editModal{{ $post->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $post->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $post->id }}">تعديل المنشور</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form للتعديل -->
                                    <form action="{{ route('posts.update', $post->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="title{{ $post->id }}" class="form-label">العنوان</label>
                                            <input type="text" name="title" id="title{{ $post->id }}"
                                                class="form-control" value="{{ $post->title }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description{{ $post->id }}" class="form-label">الوصف</label>
                                            <textarea name="description" id="description{{ $post->id }}" class="form-control">{{ $post->description }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="images{{ $post->id }}" class="form-label">الصور</label>
                                            <input type="file" name="images[]" id="images{{ $post->id }}"
                                                class="form-control" multiple>
                                            @foreach (json_decode($post->images, true) ?? [] as $image)
                                                <img src="{{ $image }}" alt="صورة" width="100">
                                            @endforeach
                                        </div>
                                        <div class="mb-3">
                                            <label for="videos{{ $post->id }}" class="form-label">الفيديوهات</label>
                                            <input type="file" name="videos[]" id="videos{{ $post->id }}"
                                                class="form-control" multiple>
                                            @foreach (json_decode($post->videos, true) ?? [] as $video)
                                                <video width="100" controls>
                                                    <source src="{{ $video }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endforeach
                                        </div>
                                        <div class="mb-3">
                                            <label for="status{{ $post->id }}" class="form-label">الحالة</label>
                                            <select name="status" id="status{{ $post->id }}" class="form-control"
                                                required>
                                                <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>
                                                    مسودة</option>
                                                <option value="published"
                                                    {{ $post->status == 'published' ? 'selected' : '' }}>منشور</option>
                                                <option value="banned" {{ $post->status == 'banned' ? 'selected' : '' }}>
                                                    محظور</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        <!-- Modal للإضافة -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">إنشاء منشور جديد</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form للإضافة -->
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="user_id" class="form-label">اختر المستخدم</label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">العنوان</label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">الوصف</label>
                                <textarea name="description" id="description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="images" class="form-label">الصور</label>
                                <input type="file" name="images[]" id="images" class="form-control" multiple>
                            </div>
                            <div class="mb-3">
                                <label for="videos" class="form-label">الفيديوهات</label>
                                <input type="file" name="videos[]" id="videos" class="form-control" multiple>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">الحالة</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="draft">مسودة</option>
                                    <option value="published">منشور</option>
                                    <option value="banned">محظور</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">إنشاء</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
