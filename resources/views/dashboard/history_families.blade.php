@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>قائمة العائلات</h1>

        <!-- زر لفتح Modal للإضافة -->
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
            إنشاء جديد
        </button>
        
        @include('components.alerts')
        <!-- جدول لعرض البيانات -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>الوصف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($historyFamilies as $historyFamily)
                    <tr>
                        <td>{{ $historyFamily->title }}</td>
                        <td>{{ $historyFamily->description }}</td>
                        <td>
                            <!-- زر التعديل -->
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#editModal{{ $historyFamily->id }}">
                                تعديل
                            </button>
                            <!-- زر الحذف -->
                            <form action="{{ route('history-families.destroy', $historyFamily->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal للتعديل لكل سجل -->
                    <div class="modal fade" id="editModal{{ $historyFamily->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $historyFamily->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $historyFamily->id }}">تعديل العائلة</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form للتعديل -->
                                    <form action="{{ route('history-families.update', $historyFamily->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="title{{ $historyFamily->id }}" class="form-label">العنوان</label>
                                            <input type="text" name="title" id="title{{ $historyFamily->id }}"
                                                class="form-control" value="{{ $historyFamily->title }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description{{ $historyFamily->id }}"
                                                class="form-label">الوصف</label>
                                            <textarea name="description" id="description{{ $historyFamily->id }}" class="form-control">{{ $historyFamily->description }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="images{{ $historyFamily->id }}" class="form-label">الصور</label>
                                            <input type="text" name="images" id="images{{ $historyFamily->id }}"
                                                class="form-control" value="{{ $historyFamily->images }}">
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
                        <h5 class="modal-title" id="addModalLabel">إنشاء عائلة جديدة</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form للإضافة -->
                        <form action="{{ route('history-families.store') }}" method="POST">
                            @csrf
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
                                <input type="text" name="images" id="images" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">إنشاء</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
