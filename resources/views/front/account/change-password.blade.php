@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ol>
            </div>
        </div>
    </section>
    <div class="container mt-5">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <!-- End Flash Messages -->

        <div class="row">
            <div class="col-md-3">
                @include('front.account.sidebar.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Change Password</h2>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('account.change-password.submit') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="old_password">Old Password</label>
                                <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control" required>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-dark" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
