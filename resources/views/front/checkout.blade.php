@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="sub-title">
                        <h2>Shipping Address</h2>
                    </div>
                    <div class="card shadow-lg border-0">
                        <div class="card-body checkout-form">
                            <div id="loading-message" style="display: none; background-color: rgba(0, 0, 0, 0.732); color: rgb(255, 208, 40); padding: 10px; border-radius: 5px;">
                                <h4><p>Please wait while we process your order...</p></h4>
                            </div>


                            <form action="{{ route('front.checkout.process') }}" method="POST" id="checkout-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{ $shippingAddress->first_name ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{ $shippingAddress->last_name ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ $shippingAddress->email ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control" required>
                                                <option value="">Select a Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{$country->id}}" data-shipping="{{$country->shippingCharge->amount ?? 0}}" {{ (isset($shippingAddress) && $shippingAddress->country_id == $country->id) ? 'selected' : '' }}>{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control" required>{{ $shippingAddress->address ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="apartment" id="apartment" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)" value="{{ $shippingAddress->apartment ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ $shippingAddress->city ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="state" id="state" class="form-control" placeholder="State" value="{{ $shippingAddress->state ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{ $shippingAddress->zip ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile No." value="{{ $shippingAddress->mobile ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control">{{ $shippingAddress->notes ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="coupon_code" value="{{ $couponCode }}">
                                <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                                <input type="hidden" name="shipping" id="shipping" value="0">
                                <input type="hidden" name="discount" value="{{ $discount }}">
                                <input type="hidden" name="grand_total" id="grand_total" value="{{ $totalAfterDiscount }}">

                                <input type="hidden" name="order_summary" id="order_summary">
                                <input type="hidden" name="payment_method_selected" id="payment_method_selected">
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-md-4">
                    <div class="sub-title">
                            <h2>Order Summary</h2>
                        </div>
                        <div class="card cart-summary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between pb-2">
                                <div>Subtotal</div>
                                <div>${{ $subtotal }}</div>
                            </div>
                            <div class="d-flex justify-content-between pb-2">
                                <div>Shipping</div>
                                <div id="order-summary-shipping">$0.00</div>
                            </div>
                            <div class="d-flex justify-content-between pb-2">
                                <div>Discount</div>
                                <div>${{ $discount }}</div>
                            </div>
                            @if($couponCode)
                            <div class="d-flex justify-content-between pb-2">
                                <div>Coupon Code</div>
                                <div>{{ $couponCode }}</div>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between summary-end">
                                <div>Total</div>
                                <div id="order-summary-total">${{ $totalAfterDiscount  }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="sub-title mt-4">
                        <h2>Payment Method</h2>
                    </div>
                    <div class="card cart-summary">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">
                                    Cash on Delivery
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="card" value="card">
                                <label class="form-check-label" for="card">
                                    Pay by Card
                                </label>
                            </div>
                            <div id="payment-card-form" style="display: none;">
                                <div class="mb-3 mt-3">
                                    <input type="text" name="card_number" id="card_number" class="form-control" placeholder="Card Number">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" name="expiry_date" id="expiry_date" class="form-control" placeholder="Expiry Date">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" name="cvv" id="cvv" class="form-control" placeholder="CVV">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Place Order</button>
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
    document.addEventListener('DOMContentLoaded', function () {
        const paymentCardForm = document.getElementById('payment-card-form');
        const codRadioButton = document.getElementById('cod');
        const cardRadioButton = document.getElementById('card');
        const countrySelect = document.getElementById('country');
        const subtotal = {{ $subtotal }};
        const discount = {{ $discount }};
        let shipping = 0;

        function updateTotal() {
            const total = subtotal + shipping - discount;
            document.getElementById('order-summary-total').textContent = '$' + total.toFixed(2);
            document.getElementById('grand_total').value = total;
        }

        countrySelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            shipping = parseFloat(selectedOption.getAttribute('data-shipping')) || 0;
            document.getElementById('order-summary-shipping').textContent = '$' + shipping.toFixed(2);
            document.getElementById('shipping').value = shipping;
            updateTotal();
        });

        // Initial shipping update if a country is pre-selected
        if (countrySelect.value) {
            const selectedOption = countrySelect.options[countrySelect.selectedIndex];
            shipping = parseFloat(selectedOption.getAttribute('data-shipping')) || 0;
            document.getElementById('order-summary-shipping').textContent = '$' + shipping.toFixed(2);
            document.getElementById('shipping').value = shipping;
            updateTotal();
        }

        codRadioButton.addEventListener('change', function () {
            if (this.checked) {
                paymentCardForm.style.display = 'none';
            }
        });

        cardRadioButton.addEventListener('change', function () {
            if (this.checked) {
                paymentCardForm.style.display = 'block';
            }
        });
    });

    $(document).ready(function() {
        $('#checkout-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            // Check if cart is empty
            var cart = {!! json_encode(session('cart', [])) !!};
            if (cart.length === 0) {
                alert('No items in the cart to checkout.');
                return;
            }

            // Show processing message
            $('#loading-message').show();

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else if (response.message) {
                        alert(response.message);
                    } else {
                        alert('Unknown response from server.');
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '\n';
                    });
                    alert(errorMessage);
                },
                complete: function() {
                    // Hide processing message
                    $('#loading-message').hide();
                }
            });
        });
    });
</script>
@endsection



