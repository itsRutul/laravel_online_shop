@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Discount Coupon</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('discount-coupons.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('discount-coupons.update', $discountCoupon->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" name="code" id="code" class="form-control" placeholder="Enter coupon code" value="{{ $discountCoupon->code }}" required>
                            </div>

                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <select name="product_name" id="product_name" class="form-control select2" placeholder="Select product (optional)">
                                    <option value="">None</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $discountCoupon->product_id == $product->id ? 'selected' : '' }}>{{ $product->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" placeholder="Enter coupon description">{{ $discountCoupon->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="max_uses">Max Uses</label>
                                <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Enter maximum number of uses (optional)" value="{{ $discountCoupon->max_uses }}">
                            </div>

                            <div class="form-group">
                                <label for="max_users">Max Users</label>
                                <input type="number" name="max_users" id="max_users" class="form-control" placeholder="Enter maximum number of users (optional)" value="{{ $discountCoupon->max_users }}">
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="fixed" {{ $discountCoupon->type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                    <option value="percentage" {{ $discountCoupon->type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="discount_amount">Discount Amount</label>
                                <input type="number" step="0.01" name="discount_amount" id="discount_amount" class="form-control" placeholder="Enter discount amount" value="{{ $discountCoupon->discount_amount }}" required>
                            </div>

                            <div class="form-group">
                                <label for="min_amount">Minimum Amount</label>
                                <input type="number" step="0.01" name="min_amount" id="min_amount" class="form-control" placeholder="Enter minimum purchase amount (optional)" value="{{ $discountCoupon->min_amount }}">
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="1" {{ $discountCoupon->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $discountCoupon->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="starts_at">Starts At</label>
                                <input type="datetime-local" name="starts_at" id="starts_at" class="form-control" value="{{ $discountCoupon->starts_at ? $discountCoupon->starts_at->format('Y-m-d\TH:i') : '' }}">
                            </div>

                            <div class="form-group">
                                <label for="expire_at">Expire At</label>
                                <input type="datetime-local" name="expire_at" id="expire_at" class="form-control" value="{{ $discountCoupon->expire_at ? $discountCoupon->expire_at->format('Y-m-d\TH:i') : '' }}">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Coupon</button>
                                <a href="{{ route('discount-coupons.index') }}" class="btn btn-outline-dark ml-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customjs')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select product",
            allowClear: true
        });
    });
</script>
@endsection
