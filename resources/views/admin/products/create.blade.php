@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('products.store') }}" id="ProductForm" name="ProductForm" method="POST" enctype="multipart/form-data">

            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Title" onkeyup="generateSlug()" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <div id="view-box-container" class="d-flex flex-wrap"></div>
                        </div>
                    </div>

                    <div class="row" id="product-gallery">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Price" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="number" step="0.01" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control" placeholder="SKU" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" checked>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control" required>
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category_id" id="category" class="form-control" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="sub_category">Sub category</label>
                                <select name="sub_category_id" id="sub_category" class="form-control">
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product brand</h2>
                            <div class="mb-3">
                                <select name="brand_id" id="brand" class="form-control">
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Attributes</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Size</label><br>
                                        @for ($i = 3; $i <= 12; $i++)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="size[]" id="size{{ $i }}" value="{{ $i }}">
                                                <label class="form-check-label" for="size{{ $i }}">{{ $i }}</label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Color</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="color[]" id="colorPink" value="pink">
                                            <label class="form-check-label" for="colorPink">Pink</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="color[]" id="colorBlue" value="blue">
                                            <label class="form-check-label" for="colorBlue">Blue</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Featured product</h2>
                            <div class="mb-3">
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
        url: "{{ route('products.store') }}",
        maxFilesize: 2, // MB
        acceptedFiles: ".png, .jpg, .jpeg, .gif",
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
                alert('Product created successfully.');
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

        var removeButton = Dropzone.createElement("<button class='btn btn-danger btn-sm'>Delete</button>");
        removeButton.style.marginTop = '10px'; // Add margin to position the delete button below the image

        removeButton.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            myDropzone.removeFile(file);
            container.parentNode.removeChild(container);
        };

        container.appendChild(imgElement);
        container.appendChild(removeButton);
        var viewBoxContainer = document.getElementById('view-box-container');
        viewBoxContainer.appendChild(container);
    };
    reader.readAsDataURL(file);
});

        }
    });
</script>
{{-- <script>
    Dropzone.autoDiscover = false;

    document.addEventListener('DOMContentLoaded', function () {
        if (!Dropzone.getElement('#file-upload')) {
            // Dropzone not attached yet, so initialize it
            Dropzone.autoDiscover = false;

            var myDropzone = new Dropzone('#file-upload', {
                paramName: "images",
                url: "{{ route('products.store') }}", // Specify the URL for file uploads
                maxFilesize: 2, // MB
                acceptedFiles: ".png, .jpg, .JFIF, .jpeg, .gif",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                addRemoveLinks: true,
                init: function () {
                    var submitButton = document.querySelector("button[type=submit]");
                    var myDropzone = this;

                    submitButton.addEventListener("click", function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (myDropzone.getQueuedFiles().length > 0) {
                            myDropzone.processQueue();
                        } else {
                            document.getElementById("ProductForm").submit();
                        }
                    });

                    this.on("sending", function (file, xhr, formData) {
                        var data = $('#ProductForm').serializeArray();
                        $.each(data, function (key, el) {
                            formData.append(el.name, el.value);
                        });
                    });

                    this.on("successmultiple", function (files, response) {
                        window.location.href = "{{ route('products.index') }}";
                        alert('Product created successfully.');
                    });

                    this.on("errormultiple", function (files, response) {
                        console.error('Error during upload:', response);
                    });

                    // Add preview images to the view box container
                    this.on("addedfile", function (file) {
                        var reader = new FileReader();
                        reader.onload = function (event) {
                            var container = document.createElement('div');
                            container.className = 'image-container';

                            var imgElement = document.createElement('img');
                            imgElement.src = event.target.result;
                            imgElement.style.width = '300px';
                            imgElement.style.height = '300px';
                            imgElement.className = 'm-2';

                            var removeButton = Dropzone.createElement("<button class='btn btn-danger btn-sm'>Delete</button>");
                            removeButton.style.marginTop = '10px'; // Add margin to position the delete button below the image

                            removeButton.onclick = function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                myDropzone.removeFile(file);
                                container.parentNode.removeChild(container);
                            };

                            container.appendChild(imgElement);
                            container.appendChild(removeButton);
                            var viewBoxContainer = document.getElementById('view-box-container');
                            viewBoxContainer.appendChild(container);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });
        }
    });
</script> --}}
@endsection

