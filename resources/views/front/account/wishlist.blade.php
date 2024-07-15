@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Wishlist</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-11">
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
                            <h2 class="h5 mb-0 pt-2 pb-2">My Wishlist</h2>
                        </div>
                        <div class="card-body p-4">
                            @foreach ($wishlistProducts as $wishlistProduct)
                            <div class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                                <div class="d-block d-sm-flex align-items-start text-center text-sm-start">
                                    <a href="{{ route('front.show', $wishlistProduct->product->id) }}"  class="d-block flex-shrink-0 mx-auto me-sm-4"  style="width: 10rem;">
                                        <img src="{{ asset('product_images/' . json_decode($wishlistProduct->product->image)[0]) }}" width="100" height="100" >
                                    </a>
                                    <div class="pt-2">
                                        <h3 class="product-title fs-base mb-2"><a href="#">{{ \Illuminate\Support\Str::limit($wishlistProduct->product->title, 15) }}</a></h3>
                                        <div class="fs-lg text-accent pt-2">${{ $wishlistProduct->product->price }}</div>
                                    </div>
                                </div>
                                <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                    <form action="{{ route('wishlist.remove') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $wishlistProduct->product_id }}">
                                        <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-trash-alt me-2"></i>Remove</button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection
