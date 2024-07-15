@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('categories.store') }}" method="POST" id="categoryForm" name="categoryForm" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" onkeyup="generateSlug()" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" readonly required>
                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image">Image</label>
                                <div id="file-upload" class="dropzone">
                                    <div class="dz-message needsclick">
                                        <span>Drop file here or click to upload</span>
                                    </div>
                                </div>
                                @error('file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="showathome">Show-At-Home</label>
                                <select name="showathome" id="is_featured" class="form-control" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
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
        paramName: "file", // The name that will be used to transfer the file
        url: "{{ route('categories.store') }}", // Your route to handle file uploads
        maxFilesize: 2, // MB
        acceptedFiles: ".png, .jpg, .jpeg, .gif", // Allowed file types
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        clickable: true, // Makes the entire dropzone clickable
        autoProcessQueue: false, // Prevent Dropzone from auto processing the queue
        addRemoveLinks: true, // Enable the remove link
        init: function() {
            var submitButton = document.querySelector("button[type=submit]");
            myDropzone = this; // closure

            submitButton.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                myDropzone.processQueue(); // Tell Dropzone to process all queued files.
            });

            this.on("sending", function(file, xhr, formData) {
                // Append all form inputs to the formData Dropzone will send
                var data = $('#categoryForm').serializeArray();
                $.each(data, function(key, el) {
                    formData.append(el.name, el.value);
                });
            });

            this.on("success", function(file, response) {
                // Handle successful uploads
                console.log(response);
                // If the upload is successful, redirect to the categories index page
                window.location.href = "{{ route('categories.index') }}";
                // You can also add a success message if needed
                alert('Category created successfully.');
            });

            this.on("error", function(file, response) {
                // Handle errors
                console.error('Error during upload:', response);
            });
        }
    });
</script>
@endsection
