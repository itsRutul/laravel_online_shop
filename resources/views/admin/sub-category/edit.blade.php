@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Sub-Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Sub-Category</h3>
            </div>
            <form action="{{ route('sub-categories.update', $subCategory->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                        <label for="category">Category</label>
                        <select name="category" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $subCategory->category_id == $category->id ? 'selected' : '' }} >{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $subCategory->name }}" onkeyup="generateSlug()" required>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" class="form-control" id="slug" value="{{ $subCategory->slug }}" required>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="1" {{ $subCategory->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $subCategory->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label for="showathome">Show-At-Home</label>
                        <select name="showathome" class="form-control" required>
                            <option value="1" {{ $subCategory->showathome == 1 ? 'selected' : '' }}>yes</option>
                            <option value="0" {{ $subCategory->showathome == 0 ? 'selected' : '' }}>no</option>
                        </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">submit</button>
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
@endsection
