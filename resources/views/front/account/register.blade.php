@extends('front.layouts.app')

@section('content')

<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item">Register</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            <div class="login-form">    
                <form id="registrationform">
                    @csrf
                    <h4 class="modal-title">Register Now</h4>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                        <span class="text-danger" id="name-error"></span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Email" id="email" name="email">
                        <span class="text-danger" id="email-error"></span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                        <span class="text-danger" id="phone-error"></span>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                        <span class="text-danger" id="password-error"></span>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">
                        <span class="text-danger" id="password_confirmation-error"></span>
                    </div>
                    <div class="form-group small">
                        <a {{-- href="{{ route('password.request') }}" --}} class="forgot-link">Forgot Password?</a>
                    </div> 
                    <button type="submit" class="btn btn-dark btn-block btn-lg">Register</button>
                </form>			
                <div class="text-center small">Already have an account? <a href="{{ route('login') }}">Login Now</a></div>
            </div>
        </div>
    </section>
</main>

@endsection

@section('customjs')
<script>
    $(document).ready(function() {
        $('#registrationform').on('submit', function(e) {
            e.preventDefault();

            $('.text-danger').html(''); // Clear previous error messages

            $.ajax({
                url: '{{ route('register.handle') }}', // Adjust this route to your controller method
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.success);
                    window.location.href = '{{ route('login') }}';
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
