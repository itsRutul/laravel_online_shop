@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">Cart</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table" id="cart">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $details)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <img src="{{ asset('product_images/' . $details['image']) }}" width="50" height="50">
                                            <h2>{{ \Illuminate\Support\Str::limit($details['name'], 15) }}</h2>
                                        </div>
                                    </td>
                                    <td>${{ $details['price'] }}</td>
                                    <td>{{ $details['size'] ?? '-' }}</td>
                                    <td>{{ $details['color'] ?? '-' }}</td>
                                    <td>
                                        <div class="input-group quantity mx-auto" style="width: 100px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 update-cart" data-id="{{ $id }}" data-action="minus">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control form-control-sm border-0 text-center" value="{{ $details['quantity'] }}">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 update-cart" data-id="{{ $id }}" data-action="plus">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ $details['price'] * $details['quantity'] }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger remove-from-cart" data-id="{{ $id }}"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card cart-summary">
                        <div class="sub-title">
                            <h2 class="bg-white">Cart Summary</h2>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between pb-2">
                                <div>Subtotal</div>
                                <div>${{ $total }}</div>
                            </div>
                            <div class="d-flex justify-content-between pb-2">
                                <div>Discount</div>
                                <div id="discount-amount">${{ $discount }}</div>
                            </div>
                            <div class="d-flex justify-content-between summary-end">
                                <div>Total</div>
                                <div id="final-total">${{ $totalAfterDiscount }}</div>
                            </div>
                            <div class="pt-5">
                                <a href="{{ route('front.checkout') }}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                    <div class="input-group apply-coupon mt-4">
                        <input type="text" id="coupon-code" placeholder="Coupon Code" class="form-control">
                        <button class="btn btn-dark" type="button" id="apply-coupon">Apply Coupon</button>
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
        $('.update-cart').click(function(e) {
            e.preventDefault();
            var ele = $(this);
            var action = ele.data('action');
            var quantityInput = ele.closest('tr').find('input');
            var quantity = parseInt(quantityInput.val());

            if (action === 'plus') {
                quantity += 1;
            } else if (action === 'minus' && quantity > 1) {
                quantity -= 1;
            }

            quantityInput.val(quantity);

            var data = {
                _token: '{{ csrf_token() }}',
                id: ele.data("id"),
                quantity: quantity
            };

            $.ajax({
                url: '{{ route('front.cart.update') }}',
                method: "patch",
                data: data,
                success: function(response) {
                    window.location.reload();
                }
            });
        });

        $('.remove-from-cart').click(function(e) {
            e.preventDefault();
            var ele = $(this);

            if (confirm("Are you sure want to remove?")) {
                $.ajax({
                    url: '{{ route('front.cart.remove') }}',
                    method: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: ele.data("id")
                    },
                    success: function(response) {
                        window.location.reload();
                    }
                });
            }
        });

        $('#apply-coupon').click(function(e) {
            e.preventDefault();
            var couponCode = $('#coupon-code').val();

            $.ajax({
                url: '{{ route('front.cart.applyCoupon') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    coupon_code: couponCode
                },
                success: function(response) {
                    if (response.success) {
                        $('#discount-amount').text('$' + response.discount);
                        $('#final-total').text('$' + response.final_total);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('An error occurred while applying the coupon.');
                }
            });
        });
        $('.btn-block').click(function(e) {
        e.preventDefault();
        window.location.href = '{{ route('front.checkout') }}';
    });

    });
</script>
@endsection
