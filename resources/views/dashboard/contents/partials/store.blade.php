<div class="mt-4">
    <h2>Store</h2>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createProductModal">
        Add New Product
    </button>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text">{{ $contents->where('type', 'product')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Colors</h5>
                    <p class="card-text">{{ 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Sizes</h5>
                    <p class="card-text">{{ 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Price</th>
                <th>Colors</th>
                <th>Sizes</th>
                <th>quantity</th>
                <th>Images</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contents->where('type', 'product') as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->colors ? implode(', ', json_decode($product->colors)) : 'N/A' }}</td>
                    <td>{{ $product->sizes ? implode(', ', json_decode($product->sizes)) : 'N/A' }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>
                        @if ($product->images)
                            @foreach (json_decode($product->images) as $image)
                                <img src="{{ Storage::url($image) }}" alt="Image" width="50" class="mr-2">
                            @endforeach
                        @else
                            No Images
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                            data-target="#editProductModal{{ $product->id }}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                            data-target="#deleteProductModal{{ $product->id }}">Delete</button>
                    </td>
                </tr>

                <!-- Edit Product Modal -->
                @include('dashboard.contents.modals.edit-product', ['product' => $product])

                <!-- Delete Product Modal -->
                @include('dashboard.contents.modals.delete-product', ['product' => $product])
            @endforeach
        </tbody>
    </table>

    <!-- Create Product Modal -->
    @include('dashboard.contents.modals.create-product')
</div>
