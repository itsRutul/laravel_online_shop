@extends('front.layouts.app')

@section('content')

<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item">Login</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-10">
        <div class="container">
            <div class="login-form">
                <form id="login-form">
                    @csrf
                    <h4 class="modal-title">Login to Your Account</h4>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Email" id="email" name="email">
                        <span class="text-danger" id="email-error"></span>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                        <span class="text-danger" id="password-error"></span>
                    </div>
                    <div class="form-group small">
                        <a {{-- href="{{ route('password.request') }}" --}} class="forgot-link">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-dark btn-block btn-lg">Login</button>
                </form>
                <div class="text-center small">Don't have an account? <a href="{{ route('register') }}">Sign up</a></div>
            </div>
        </div>
    </section>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#login-form').on('submit', function(e) {
            e.preventDefault();

            $('.text-danger').html(''); // Clear previous error messages

            $.ajax({
                url: '{{ route('login.handle') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Transfer wishlist items from local storage to cookies
                    let wishlist = localStorage.getItem('wishlist');
                    if (wishlist) {
                        document.cookie = "wishlist=" + wishlist + "; path=/";
                        localStorage.removeItem('wishlist');
                    }

                    // Redirect to home page or another desired page
                    window.location.href = '{{ route('front.home') }}';
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        for (var key in errors) {
                            $('#' + key + '-error').html(errors[key][0]);
                        }
                    } else if (xhr.status === 401) {
                        alert(xhr.responseJSON.error);
                    }
                }
            });
        });
    });
</script>

@endsection
