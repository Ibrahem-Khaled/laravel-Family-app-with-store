@extends('layouts.app')

@section('title', 'Contents Management')

@section('content')
    <div class="container">
        <h1>Contents Management</h1>

        @include('components.alerts')
        <!-- Tabs for Store and Articles -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="store-tab" data-toggle="tab" data-target="#store" type="button"
                    role="tab" aria-controls="store" aria-selected="true">Store</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="articles-tab" data-toggle="tab" data-target="#articles" type="button"
                    role="tab" aria-controls="articles" aria-selected="false">Articles</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Store Tab -->
            <div class="tab-pane fade show active" id="store" role="tabpanel" aria-labelledby="store-tab">
                @include('dashboard.contents.partials.store')
            </div>

            <!-- Articles Tab -->
            <div class="tab-pane fade" id="articles" role="tabpanel" aria-labelledby="articles-tab">
                @include('dashboard.contents.partials.articles')
            </div>
        </div>
    </div>
@endsection
