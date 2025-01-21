<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1"
    aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">Edit Product</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('contents.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Sub-Category -->
                    <div class="form-group">
                        <label for="sub_category_id">Sub-Category</label>
                        <select name="sub_category_id" class="form-control" required>
                            @foreach ($subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}"
                                    {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }}>
                                    {{ $subCategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- User -->
                    <div class="form-group">
                        <label for="user_id">User</label>
                        <select name="user_id" class="form-control" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $product->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Title -->
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $product->title }}"
                            required>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control">{{ $product->description }}</textarea>
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" name="price" class="form-control" value="{{ $product->price }}"
                            required>
                    </div>

                    <!-- Colors -->
                    <div class="form-group">
                        <label for="colors">Colors</label>
                        <div id="colors-container">
                            @foreach (json_decode($product->colors ?? '[]') as $color)
                                <div class="input-group mb-2">
                                    <input type="text" name="colors[]" class="form-control"
                                        value="{{ $color }}">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger remove-color">-</button>
                                    </div>
                                </div>
                            @endforeach
                            <div class="input-group mb-2">
                                <input type="text" name="colors[]" class="form-control" placeholder="Enter a color">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success add-color">+</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Sizes -->
                    <div class="form-group">
                        <label for="sizes">Sizes</label>
                        <div id="sizes-container">
                            @foreach (json_decode($product->sizes ?? '[]') as $size)
                                <div class="input-group mb-2">
                                    <input type="text" name="sizes[]" class="form-control"
                                        value="{{ $size }}">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger remove-size">-</button>
                                    </div>
                                </div>
                            @endforeach
                            <div class="input-group mb-2">
                                <input type="text" name="sizes[]" class="form-control" placeholder="Enter a size">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success add-size">+</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="{{ $product->quantity }}"
                            required>
                    </div>

                    <!-- Images -->
                    <div class="form-group">
                        <label for="images">Images</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                        <div class="row mt-3">
                            @foreach (json_decode($product->images) as $image)
                                <div class="col-md-3">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Product Image" class="img-fluid">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Hidden Type -->
                    <input hidden name="type" value="{{ $product->type }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script to handle dynamic inputs -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add new color input
        document.querySelector('.add-color').addEventListener('click', function() {
            const container = document.getElementById('colors-container');
            const newInput = document.createElement('div');
            newInput.classList.add('input-group', 'mb-2');
            newInput.innerHTML = `
                <input type="text" name="colors[]" class="form-control" placeholder="Enter a color">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-color">-</button>
                </div>
            `;
            container.appendChild(newInput);
        });

        // Add new size input
        document.querySelector('.add-size').addEventListener('click', function() {
            const container = document.getElementById('sizes-container');
            const newInput = document.createElement('div');
            newInput.classList.add('input-group', 'mb-2');
            newInput.innerHTML = `
                <input type="text" name="sizes[]" class="form-control" placeholder="Enter a size">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-size">-</button>
                </div>
            `;
            container.appendChild(newInput);
        });

        // Remove color input
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-color')) {
                e.target.closest('.input-group').remove();
            }
        });

        // Remove size input
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-size')) {
                e.target.closest('.input-group').remove();
            }
        });
    });
</script>
