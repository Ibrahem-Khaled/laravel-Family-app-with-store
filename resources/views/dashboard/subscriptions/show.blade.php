@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>تفاصيل الاشتراك: {{ $subscription->name }}</h1>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">معلومات الاشتراك</h5>
                <p class="card-text"><strong>الوصف:</strong> {{ $subscription->description }}</p>
                <p class="card-text"><strong>السعر:</strong> {{ $subscription->price }}</p>
                <p class="card-text"><strong>المدة:</strong> {{ $subscription->duration }} يوم</p>
            </div>
        </div>

        <h3>المشتركون</h3>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addUserModal">إضافة مستخدم</button>
        @include('components.alerts')
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>حالة الاشتراك</th>
                    <th>تاريخ البدء</th>
                    <th>تاريخ الانتهاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscription->users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->pivot->active)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-danger">غير نشط</span>
                            @endif
                        </td>
                        <td>{{ $user->pivot->start_at }}</td>
                        <td>{{ $user->pivot->end_at }}</td>
                        <td>
                            <form action="{{ route('subscriptions.removeUser', $subscription->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal لإضافة مستخدم -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">إضافة مستخدم إلى الاشتراك</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <form action="{{ route('subscriptions.addUser', $subscription->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="user_id">اختر مستخدم</label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-success">إضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
