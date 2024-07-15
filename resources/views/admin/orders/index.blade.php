@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders</h1>
                </div>
                <div class="col-sm-6 text-right"></div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Date Purchased</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr onclick="window.location='{{ route('orders.details', $order->id) }}'">
                                    <td><a href="{{ route('orders.details', $order->id) }}">{{ $order->id }}</a></td>
                                    <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                                    <td>{{ $order->email }}</td>
                                    <td>{{ $order->mobile }}</td>
                                    <td>
                                        @php
                                            $statusBadge = '';
                                            switch ($order->status) {
                                                case 0:
                                                    $statusBadge = 'badge bg-orange';
                                                    break;
                                                case 1:
                                                    $statusBadge = 'badge bg-yellow';
                                                    break;
                                                case 2:
                                                    $statusBadge = 'badge bg-green';
                                                    break;
                                                case 3:
                                                    $statusBadge = 'badge bg-red';
                                                    break;
                                            }
                                        @endphp
                                        <span class="{{ $statusBadge }}">
                                            @switch($order->status)
                                                @case(0)
                                                    Pending
                                                    @break
                                                @case(1)
                                                    Shipped
                                                    @break
                                                @case(2)
                                                    Delivered
                                                    @break
                                                @case(3)
                                                    Cancelled
                                                    @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>${{ $order->grand_total }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
