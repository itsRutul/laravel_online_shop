@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                <li class="breadcrumb-item">Order Details</li>
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
                        <h2 class="h5 mb-0 pt-2 pb-2">Order Details</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-6 col-lg-3">
                                <h6 class="heading-xxxs text-muted">Order No:</h6>
                                <p class="mb-lg-0 fs-sm fw-bold">{{ $order->id }}</p>
                            </div>
                            <div class="col-6 col-lg-3">
                                <h6 class="heading-xxxs text-muted">Date Purchased:</h6>
                                <p class="mb-lg-0 fs-sm fw-bold">{{ $order->created_at->format('d M, Y') }}</p>
                            </div>
                            <div class="col-6 col-lg-3">
                                <!-- Heading -->
                                <h6 class="heading-xxxs text-muted">Status:</h6>
                                <!-- Text -->
                                <p class="mb-0 fs-sm fw-bold">
                                Awating Delivery
                                </p>
                            </div>
                            <div class="col-6 col-lg-3">
                                <h6 class="heading-xxxs text-muted">Total:</h6>
                                <p class="mb-0 fs-sm fw-bold">${{ $order->grand_total }}</p>
                            </div>
                        </div>

                        <h6 class="mb-4 h5">Order Items</h6>
                        <ul class="list-group mb-4">
                            @foreach($order->orderItems as $item)
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-md-3 col-xl-2">
                                            <a href="{{ route('front.show', $item->product->id) }}" class="product-img">
                                                <img class="img-fluid" src="{{ asset('product_images/' . json_decode($item->product->image)[0]) }}">
                                            </a>
                                        </div>
                                        <div class="col">
                                            <p class="mb-4 fs-sm fw-bold">
                                                <a class="text-body" href="{{ route('front.show', $item->product->id) }}">{{ $item->product->title }}</a> <br>
                                                <span class="text-muted">${{ $item->price }} x {{ $item->qty }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <h6 class="mb-3 h5">Order Total</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex">
                                <span>Subtotal</span>
                                <span class="ms-auto">${{ $order->subtotal }}</span>
                            </li>
                            <li class="list-group-item d-flex">
                                <span>Discount</span>
                                <span class="ms-auto">${{ $order->discount }}</span>
                            </li>
                            <li class="list-group-item d-flex">
                                <span>Shipping</span>
                                <span class="ms-auto">${{ $order->shipping }}</span>
                            </li>
                            <li class="list-group-item d-flex fs-lg fw-bold">
                                <span>Total</span>
                                <span class="ms-auto">${{ $order->grand_total }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
@endsection
