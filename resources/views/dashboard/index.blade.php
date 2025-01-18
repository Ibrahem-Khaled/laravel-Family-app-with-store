@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center mb-4">لوحة التحكم</h1>

        <!-- الإحصائيات الإجمالية -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">المستخدمين</h5>
                        <p class="card-text">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">الفئات الرئيسية</h5>
                        <p class="card-text">{{ $totalCategories }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">الفئات الفرعية</h5>
                        <p class="card-text">{{ $totalSubCategories }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">المحتويات</h5>
                        <p class="card-text">{{ $totalContents }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">الملفات الصوتية</h5>
                        <p class="card-text">{{ $totalAudioFiles }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">إجمالي المشاهدات</h5>
                        <p class="card-text">{{ $totalViews }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- الرسوم البيانية -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">عدد الفئات الفرعية لكل فئة رئيسية</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="subCategoryChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">إحصائيات أخرى (مثال)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="exampleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // رسم بياني لعدد الفئات الفرعية لكل فئة رئيسية
        const subCategoryChart = new Chart(document.getElementById('subCategoryChart'), {
            type: 'bar',
            data: {
                labels: @json($categoryNames),
                datasets: [{
                    label: 'عدد الفئات الفرعية',
                    data: @json($subCategoryCounts),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // رسم بياني مثال آخر
        const exampleChart = new Chart(document.getElementById('exampleChart'), {
            type: 'line',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'مثال بيانات',
                    data: [12, 19, 3, 5, 2, 3],
                    fill: false,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection