@extends('front.layouts.app')

@section('content')

<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">My Account</a></li>
                    <li class="breadcrumb-item">profile</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-11">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('front.account.sidebar.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <form id="updateprofileForm">
                                    @csrf
                                    <div class="mb-3">               
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control">
                                        <span class="text-danger" id="name-error"></span>
                                    </div>
                                    <div class="mb-3">            
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-control">
                                        <span class="text-danger" id="email-error"></span>
                                    </div>
                                    <div class="mb-3">                                    
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone" value="{{ $user->phone }}" class="form-control">
                                        <span class="text-danger" id="phone-error"></span>
                                    </div>
                                    <div class="mb-3">                                    
                                        <label for="address">Address</label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="5">{{ $user->address }}</textarea>
                                        <span class="text-danger" id="address-error"></span>
                                    </div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-dark">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('customjs')
<script>
    $(document).ready(function() {
        $('#updateprofileForm').on('submit', function(e) {
            e.preventDefault();

            $('.text-danger').html(''); // Clear previous error messages

            $.ajax({
                url: '{{ route('account.profile.update') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.success);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        for (var key in errors) {
                            $('#' + key + '-error').html(errors[key][0]);
                        }
                    }
                }
            });
        });
    });
</script>

@endsection
