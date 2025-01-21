<div class="mt-4">
    <h2>Articles</h2>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createArticleModal">
        Add New Article
    </button>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Articles</h5>
                    <p class="card-text">{{ $contents->where('type', 'article')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles Table -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Images</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contents->where('type', 'article') as $article)
                <tr>
                    <td>{{ $article->id }}</td>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->user->name }}</td>
                    <td>
                        @if ($article->images)
                            @foreach (json_decode($article->images) as $image)
                                <img src="{{ Storage::url($image) }}" alt="Image" width="50" class="mr-2">
                            @endforeach
                        @else
                            No Images
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                            data-target="#editArticleModal{{ $article->id }}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                            data-target="#deleteArticleModal{{ $article->id }}">Delete</button>
                    </td>
                </tr>

                <!-- Edit Article Modal -->
                @include('dashboard.contents.modals.edit-article', ['article' => $article])

                <!-- Delete Article Modal -->
                @include('dashboard.contents.modals.delete-article', ['article' => $article])
            @endforeach
        </tbody>
    </table>

    <!-- Create Article Modal -->
    @include('dashboard.contents.modals.create-article')
</div>
