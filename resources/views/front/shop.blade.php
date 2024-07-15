@extends('front.layouts.app')

@section('content')
<section class="section-6 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 sidebar">
                <div class="sub-title">
                    <h2>Categories</h2>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionExample">
                            @foreach($categories as $category)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $category->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $category->id }}" aria-expanded="false" aria-controls="collapse{{ $category->id }}">
                                            {{ $category->name }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $category->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $category->id }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="navbar-nav">
                                                <!-- Category Filters -->
                                                @foreach($category->subCategory as $subCategory)
                                                    <a href="javascript:void(0);" class="nav-item nav-link subcategory-filter" data-category-id="{{ $category->id }}" data-subcategory-id="{{ $subCategory->id }}">
                                                        {{ $subCategory->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="sub-title mt-5">
                    <h2>Brand</h2>
                </div>

                <div class="card">
                    <div class="card-body">
                        <!-- Brand Filters -->
                        @foreach($brands as $brand)
                            <div class="form-check mb-2">
                                <input class="form-check-input brand-filter" type="checkbox" value="{{ $brand->id }}" id="brand{{ $brand->id }}" name="brands[]" {{ in_array($brand->id, explode(',', request('brands', ''))) ? 'checked' : '' }}>
                                <label class="form-check-label" for="brand{{ $brand->id }}">
                                    {{ $brand->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="sub-title mt-5">
                    <h2>Price</h2>
                </div>

                <div class="card">
                    <div class="card-body">

                        @foreach($priceRanges as $priceRange)
                        <div class="form-check mb-2">
                            <input class="form-check-input price-range-filter" type="checkbox" name="price_range[]" value="{{ $priceRange['value'] }}" id="price{{ $loop->index }}" {{ in_array($priceRange['value'], explode(',', request('price_range', ''))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="price{{ $loop->index }}">
                                {{ $priceRange['label'] }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-end mb-4">
                            <div class="ml-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Sorting</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <!-- Sorting Options with Filter Retention -->
                                        <a class="dropdown-item" href="{{ route('front.shop', array_merge(request()->query(), ['sort' => 'latest'])) }}">Latest</a>
                                        <a class="dropdown-item" href="{{ route('front.shop', array_merge(request()->query(), ['sort' => 'price_high'])) }}">Price High</a>
                                        <a class="dropdown-item" href="{{ route('front.shop', array_merge(request()->query(), ['sort' => 'price_low'])) }}">Price Low</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Display -->
                    @foreach($products as $product)
                        <div class="col-md-4">
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="{{ route('front.show', $product->id) }}" class="product-img">
                                        <img class="card-img-top" src="{{ asset('product_images/' . json_decode($product->image)[0]) }}" alt="{{ $product->title }}">
                                    </a>
                                    <form action="{{ route('wishlist.add') }}" method="post" id="wishlist-form-{{ $product->id }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="hidden-button"></button>
                                    </form>

                                    <!-- The <a> tag to trigger form submission -->
                                    <a href="javascript:void(0);" class="whishlist" onclick="document.getElementById('wishlist-form-{{ $product->id }}').submit()">
                                        <i class="far fa-heart"></i>
                                    </a>

                                   {{--  <div class="product-action">

                                            <a class="btn btn-dark" href="{{ route('front.cart.add', $product->id) }}">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a>


                                    </div> --}}
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="{{ route('front.show', $product->id) }}">{{ $product->title }}</a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>${{ $product->price }}</strong></span>
                                        @if($product->original_price)
                                            <span class="h6 text-underline"><del>${{ $product->original_price }}</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-12 pt-5">
                    <!-- Pagination Links with Filter Retention -->
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .hidden-button {
        display: none;
    }
    </style>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const applyFilters = () => {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);

            // Update the category and subcategory filters
            document.querySelectorAll('.subcategory-filter').forEach(item => {
                item.addEventListener('click', function () {
                    params.set('category', this.getAttribute('data-category-id'));
                    params.set('subcategory', this.getAttribute('data-subcategory-id'));
                    window.location.href = url.pathname + '?' + params.toString();
                });
            });

            // Update the brand filters
            document.querySelectorAll('.brand-filter').forEach(item => {
                item.addEventListener('change', function () {
                    let selectedBrands = [];
                    document.querySelectorAll('.brand-filter:checked').forEach(checkedItem => {
                        selectedBrands.push(checkedItem.value);
                    });
                    if (selectedBrands.length > 0) {
                        params.set('brands', selectedBrands.join(','));
                    } else {
                        params.delete('brands');
                    }
                    window.location.href = url.pathname + '?' + params.toString();
                });
            });

            // Update the price range filters
            document.querySelectorAll('.price-range-filter').forEach(item => {
                    item.addEventListener('change', function () {
                     if (this.checked) {
                        params.set('price_range', this.value);
                    } else {
                        params.delete('price_range');
                    }
                    window.location.href = url.pathname + '?' + params.toString();
                });
            });

        };

        applyFilters();
    });
</script>

@endsection
