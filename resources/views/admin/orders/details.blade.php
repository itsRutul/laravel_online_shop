@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Order: #{{ $order->id }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header pt-3">
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                <h1 class="h5 mb-3">Shipping Address</h1>
                                <address>
                                    <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                                    {{ $order->address }}<br>
                                    {{ $order->city }}, {{ $order->state }} {{ $order->zip }}<br>
                                    {{ $order->country->name ?? '' }}<br>
                                    Phone: {{ $order->mobile }}<br>
                                    Email: {{ $order->email }}
                                </address>
                            </div>

                            <div class="col-sm-4 invoice-col">
                                <b>Invoice #{{ $order->id }}</b><br><br>
                                <b>Total:</b> ${{ number_format($order->grand_total, 2) }}<br>
                                <b>Status:</b>
                                @switch($order->status)
                                    @case(0)
                                        <span class="text-orange">Pending</span>
                                        @break
                                    @case(1)
                                        <span class="text-yellow">Shipped</span>
                                        @break
                                    @case(2)
                                        <span class="text-green">Delivered</span>
                                        @break
                                    @case(3)
                                        <span class="text-red">Cancelled</span>
                                        @break
                                @endswitch
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th width="100">Price</th>
                                    <th width="100">Qty</th>
                                    <th width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>${{ number_format($item->price * $item->qty, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="3" class="text-right">Subtotal:</th>
                                    <td>${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Discount:</th>
                                    <td>({{ $order->coupon_code}})${{ number_format($order->discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Shipping:</th>
                                    <td>${{ number_format($order->shipping, 2) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Grand Total:</th>
                                    <td>${{ number_format($order->grand_total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Order Status</h2>
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Shipped</option>
                                    <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Delivered</option>
                                    <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Send Invoice Email</h2>
                        <div class="mb-3">
                            <select name="recipient" id="recipient" class="form-control">
                                <option value="customer">Customer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
