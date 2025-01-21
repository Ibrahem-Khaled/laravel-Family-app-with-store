<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Create New Product</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Sub-Category -->
                    <div class="form-group">
                        <label for="sub_category_id">Sub-Category</label>
                        <select name="sub_category_id" class="form-control" required>
                            @foreach ($subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- User -->
                    <div class="form-group">
                        <label for="user_id">User</label>
                        <select name="user_id" class="form-control" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Title -->
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>

                    <!-- Colors -->
                    <div class="form-group">
                        <label for="colors">Colors</label>
                        <div id="colors-container">
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
                        <input type="number" name="quantity" class="form-control" required>
                    </div>

                    <!-- Images -->
                    <div class="form-group">
                        <label for="images">Images</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>

                    <!-- Hidden Type -->
                    <input hidden name="type" value="product">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
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
