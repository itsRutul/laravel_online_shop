@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('categories.update', $category->id) }}" method="POST" id="categoryForm" name="categoryForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $category->name) }}" onkeyup="generateSlug()">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" class="form-control" id="slug" value="{{ $category->slug }}" required>
                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image">Image</label>
                                <div id="file-upload" class="dropzone"></div>
                                @error('file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="showathome">Show-At-Home</label>
                                <select name="showathome" class="form-control" id="showathome">
                                    <option value="1" {{ $category->showathome == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $category->showathome == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection

@section('customjs')
<script>
    function generateSlug() {
        const name = document.getElementById('name').value;
        const slug = name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
        document.getElementById('slug').value = slug;
    }
</script>

<script>
    Dropzone.autoDiscover = false;

    // Initialize Dropzone
    var myDropzone = new Dropzone('#file-upload', {
        paramName: "file",
        url: "{{ route('categories.update', $category->id) }}",
        method: 'POST', // Use POST to handle PUT method
        maxFilesize: 2, // MB
        acceptedFiles: ".png, .jpg, .jpeg, .gif",
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'X-HTTP-Method-Override': 'PUT' // This is required to override the method to PUT
        },
        autoProcessQueue: false,
        addRemoveLinks: true,
        init: function() {
            var submitButton = document.querySelector("button[type=submit]");
            myDropzone = this;

            // Handle submit button
            submitButton.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (myDropzone.getQueuedFiles().length > 0) {
                    myDropzone.processQueue();
                } else {
                    document.getElementById("categoryForm").submit();
                }
            });

            // Add existing image to dropzone
            var existingImage = "{{ asset('category_images/' . $category->image) }}";
            if (existingImage) {
                var mockFile = { name: "{{ $category->image }}", size: 12345 };
                myDropzone.displayExistingFile(mockFile, existingImage);
                // Add a flag to mark the file as existing
                mockFile.existing = true;
            }

            // Append form data to request
            this.on("sending", function(file, xhr, formData) {
                var data = $('#categoryForm').serializeArray();
                $.each(data, function(key, el) {
                    formData.append(el.name, el.value);
                });
            });

            // Handle success
            this.on("success", function(file, response) {
                console.log(response);
                window.location.href = "{{ route('categories.index') }}";
                alert('Category updated successfully.');
            });

            // Handle error
            this.on("error", function(file, response) {
                console.error('Error during upload:', response);
            });

            // Handle file removal
            this.on("removedfile", function(file) {
                if (file.existing) {
                    $.ajax({
                        url: "{{ route('categories.deleteImage', $category->id) }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            filename: file.name
                        },
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(response) {
                            console.error('Error removing file:', response);
                        }
                    });
                }
            });
        }
    });
</script>
@endsection
