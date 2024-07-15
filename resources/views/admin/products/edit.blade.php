@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('products.update', $product->id) }}" id="ProductForm" name="ProductForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <!-- Product details -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <!-- Title and Slug -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ $product->title }}" onkeyup="generateSlug()" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $product->slug }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description" required>{{ $product->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="images">Images</label>
                                <div id="file-upload" class="dropzone"></div>
                            </div>
                        </div>
                    </div>

                    <!-- View Box Container -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div id="view-box-container" class="d-flex flex-wrap">
                                @foreach(json_decode($product->image) as $image)
                                    <div class="image-container m-2">
                                        <img src="{{ asset('product_images/' . $image) }}" alt="{{ $product->title }}" style="width: 300px; height: 300px;">
                                        <button type="button" class="btn btn-danger btn-sm mt-2 delete-image" data-image="{{ $image }}">Delete</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Price" value="{{ $product->price }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare at Price</label>
                                        <input type="number" step="0.01" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price" value="{{ $product->compare_price }}">
                                        <p class="text-muted mt-3">
                                            To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventory</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                        <input type="text" name="sku" id="sku" class="form-control" placeholder="SKU" value="{{ $product->sku }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" value="{{ $product->barcode }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" {{ $product->track_qty ? 'checked' : '' }}>
                                            <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" value="{{ $product->qty }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Product status -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control" required>
                                    <option value="1" {{ $product->status ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$product->status ? 'selected' : '' }}>Block</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Product category -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category_id" id="category" class="form-control" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="subcategory">Sub-Category</label>
                                <select name="sub_category_id" id="sub_category_id" class="form-control" required>
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="brand">Brand</label>
                                <select name="brand_id" id="brand" class="form-control">
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Product -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Featured Product</h2>
                            <div class="mb-3">
                                <select name="is_featured" id="is_featured" class="form-control" required>
                                    <option value="yes" {{ $product->is_featured == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ $product->is_featured == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('customjs')
<script>
    function generateSlug() {
        const title = document.getElementById('title').value;
        const slug = title.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
        document.getElementById('slug').value = slug;
    }
</script>
<script>
    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone('#file-upload', {
        paramName: "images",
        url: "{{ route('products.update', $product->id) }}",
        maxFilesize: 2, // MB
        acceptedFiles: ".png, .jpg, .JFIF, .jpeg, .gif",
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 10,
        addRemoveLinks: true,
        init: function() {
            var submitButton = document.querySelector("button[type=submit]");
            var myDropzone = this;

            submitButton.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (myDropzone.getQueuedFiles().length > 0) {
                    myDropzone.processQueue();
                } else {
                    document.getElementById("ProductForm").submit();
                }
            });

            this.on("sending", function(file, xhr, formData) {
                var data = $('#ProductForm').serializeArray();
                $.each(data, function(key, el) {
                    formData.append(el.name, el.value);
                });
            });

            this.on("successmultiple", function(files, response) {
                window.location.href = "{{ route('products.index') }}";
                alert('Product updated successfully.');
            });

            this.on("errormultiple", function(files, response) {
                console.error('Error during upload:', response);
            });

            // Add preview images to the view box container
            this.on("addedfile", function(file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var container = document.createElement('div');
                    container.className = 'image-container';

                    var imgElement = document.createElement('img');
                    imgElement.src = event.target.result;
                    imgElement.style.width = '300px';
                    imgElement.style.height = '300px';
                    imgElement.className = 'm-2';

                    var removeButton = Dropzone.createElement('<button class="btn btn-danger btn-sm mt-2 delete-image">Delete</button>');
                    removeButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        $.ajax({
                            url: "{{ route('products.deleteImage', $product->id) }}",
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}",
                                image: file.name
                            },
                            success: function(data) {
                                if (data.success) {
                                    container.remove();
                                } else {
                                    alert(data.message);
                                }
                            },
                            error: function(err) {
                                alert('Error deleting image');
                            }
                        });
                    });

                    container.appendChild(imgElement);
                    container.appendChild(removeButton);
                    document.getElementById('view-box-container').appendChild(container);
                };
                reader.readAsDataURL(file);
            });
        }
    });

    // Handle deleting existing images
    document.querySelectorAll('.delete-image').forEach(function(button) {
        button.addEventListener('click', function() {
            var imageName = this.dataset.image;
            var container = this.parentElement;

            $.ajax({
                url: "{{ route('products.deleteImage', $product->id) }}",
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: imageName
                },
                success: function(data) {
                    if (data.success) {
                        container.remove();
                    } else {
                        alert(data.message);
                    }
                },
                error: function(err) {
                    alert('Error deleting image');
                }
            });
        });
    });
</script>
@endsection
