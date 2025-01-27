@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>الاشتراكات</h1>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">إنشاء اشتراك</button>
        @include('components.alerts')
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الوصف</th>
                    <th>السعر</th>
                    <th>المدة (أيام)</th>
                    <th>الميزات</th>
                    <th>الصورة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscriptions as $subscription)
                    <tr>
                        <td>{{ $subscription->name }}</td>
                        <td>{{ $subscription->description }}</td>
                        <td>{{ $subscription->price }}</td>
                        <td>{{ $subscription->duration }}</td>
                        <td>
                            @if ($subscription->features)
                                @foreach (json_decode($subscription->features, true) as $key => $value)
                                    <strong>{{ $key }}:</strong> {{ $value }}<br>
                                @endforeach
                            @else
                                لا توجد ميزات
                            @endif
                        </td>
                        <td>
                            <img src="{{ asset('storage/' . $subscription->image) }}" alt="{{ $subscription->name }}"
                                style="width: 100px; height: 100px;">
                        </td>
                        <td>
                            <a href="{{ route('subscriptions.show', $subscription->id) }}" class="btn btn-info">عرض
                                التفاصيل</a>
                            <button class="btn btn-warning" data-toggle="modal"
                                data-target="#editModal{{ $subscription->id }}">تعديل</button>
                            <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST"
                                style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $subscription->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $subscription->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $subscription->id }}">تعديل الاشتراك
                                    </h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="إغلاق"></button>
                                </div>
                                <form method="POST" action="{{ route('subscriptions.update', $subscription->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name">الاسم</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                value="{{ $subscription->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">الوصف</label>
                                            <input type="text" name="description" id="description" class="form-control"
                                                value="{{ $subscription->description }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="price">السعر</label>
                                            <input type="number" name="price" id="price" class="form-control"
                                                step="0.01" value="{{ $subscription->price }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="duration">المدة (أيام)</label>
                                            <input type="number" name="duration" id="duration" class="form-control"
                                                value="{{ $subscription->duration }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="features">الميزات</label>
                                            <div id="featuresContainer{{ $subscription->id }}">
                                                @if ($subscription->features)
                                                    @foreach (json_decode($subscription->features, true) as $key => $value)
                                                        <div class="feature-input">
                                                            <input type="text" class="form-control mb-2"
                                                                name="featureKey[]" placeholder="اسم الميزة"
                                                                value="{{ $key }}">
                                                            <input type="text" class="form-control mb-2"
                                                                name="featureValue[]" placeholder="قيمة الميزة"
                                                                value="{{ $value }}">
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-secondary"
                                                onclick="addFeatureInput('featuresContainer{{ $subscription->id }}')">إضافة
                                                ميزة</button>
                                        </div>
                                        <div class="form-group">
                                            <label for="payment_url">رابط الدفع</label>
                                            <input type="url" name="payment_url" id="payment_url" class="form-control"
                                                value="{{ $subscription->payment_url }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">الصورة</label>
                                            <input type="file" name="image" id="image" class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                        <button type="submit" class="btn btn-success">تحديث</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">إنشاء اشتراك</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <form method="POST" action="{{ route('subscriptions.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">الاسم</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="description">الوصف</label>
                                <input type="text" name="description" id="description" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="price">السعر</label>
                                <input type="number" name="price" id="price" class="form-control" step="0.01"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="duration">المدة (أيام)</label>
                                <input type="number" name="duration" id="duration" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="features">الميزات</label>
                                <div id="featuresContainer">
                                    <!-- Dynamic feature inputs will be added here -->
                                </div>
                                <button type="button" class="btn btn-secondary"
                                    onclick="addFeatureInput('featuresContainer')">إضافة ميزة</button>
                            </div>
                            <div class="form-group">
                                <label for="payment_url">رابط الدفع</label>
                                <input type="url" name="payment_url" id="payment_url" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="image">الصورة</label>
                                <input type="file" name="image" id="image" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-success">إنشاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
        function addFeatureInput(containerId) {
            const container = document.getElementById(containerId);
            const featureInput = document.createElement('div');
            featureInput.classList.add('feature-input');
            featureInput.innerHTML = `
                <input type="text" class="form-control mb-2" name="featureKey[]" placeholder="اسم الميزة">
                <input type="text" class="form-control mb-2" name="featureValue[]" placeholder="قيمة الميزة">
            `;
            container.appendChild(featureInput);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const featureKeys = form.querySelectorAll('input[name="featureKey[]"]');
                    const featureValues = form.querySelectorAll('input[name="featureValue[]"]');
                    const features = {};

                    featureKeys.forEach((keyInput, index) => {
                        const key = keyInput.value.trim();
                        const value = featureValues[index].value.trim();
                        if (key && value) {
                            features[key] = value;
                        }
                    });

                    const featuresInput = document.createElement('input');
                    featuresInput.type = 'hidden';
                    featuresInput.name = 'features';
                    featuresInput.value = JSON.stringify(features);
                    form.appendChild(featuresInput);
                });
            });
        });
    </script>
@endsection
