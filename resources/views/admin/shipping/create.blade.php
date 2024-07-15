@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Shipping</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('shippings.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form id="shippingForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="country_id">Country</label>
                                <select name="country_id" class="form-control" id="country_id" required>
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="country_id_error"></span>
                                <span class="text-danger" id="custom_country_id_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount">Amount</label>
                                <input type="text" name="amount" class="form-control" id="amount" required>
                                <span class="text-danger" id="amount_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="{{ route('shippings.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customjs')
<script>
$('#shippingForm').on('submit', function(e) {
    e.preventDefault();

    // Clear previous error messages
    $('#country_id_error').text('');
    $('#amount_error').text('');
    $('#custom_country_id_error').text('');

    $.ajax({
        url: "{{ route('shippings.store') }}",
        method: "POST",
        data: $(this).serialize(),
        success: function(response) {
            console.log('Success Response: ', response);
            if (response.success) {
                window.location.href = "{{ route('shippings.index') }}?message=" + encodeURIComponent(response.message);
            }
        },
        error: function(xhr, status, error) {
            var response = xhr.responseJSON;
            console.log('Error Response: ', response);

            if (response.message) {
                $('#custom_country_id_error').text(response.message);
            }

            var errors = response.errors;
            if (errors) {
                $('#country_id_error').text(errors.country_id ? errors.country_id[0] : '');
                $('#amount_error').text(errors.amount ? errors.amount[0] : '');
            }
        }
    });
});
</script>
@endsection
