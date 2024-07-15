@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-9 pt-4 thank-you-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1>Thank You, {{ $firstName }}! 😊</h1>
                    <p>Your order has been placed successfully.</p>
                    <p>Your order ID is: {{ $orderId }}</p>
                    <a href="{{ route('front.shop') }}" class="btn btn-primary">Continue Shopping</a>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .thank-you-section {
        margin-top: 200px;
        margin-bottom: 200px;
    }
</style>
@endsection
