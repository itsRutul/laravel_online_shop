@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-1">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assests/images/carousel-1-m.jpg') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assests/images/carousel-1.jpg') }}" />
                        <img src="{{ asset('front-assests/images/carousel-1.jpg') }}" alt="" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Kids Fashion</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assests/images/carousel-2-m.jpg') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assests/images/carousel-2.jpg') }}" />
                        <img src="{{ asset('front-assests/images/carousel-2.jpg') }}" alt="" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Womens Fashion</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assests/images/carousel-3-m.jpg') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assests/images/carousel-3.jpg') }}" />
                        <img src="{{ asset('front-assests/images/carousel-3.jpg') }}" alt="" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Shop Online at Flat 70% off on Branded Clothes</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <section class="section-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Quality Product</h2>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Free Shipping</h2>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">14-Day Return</h2>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">24/7 Support</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-3">
        <div class="container">
            <div class="section-title">
                <h2>Categories</h2>
            </div>
            <div class="row pb-3">
                @foreach($categories as $category)
                <div class="col-lg-3">
                    <a href="{{ route('front.shop', ['category' => $category->id]) }}" class="d-block">
                        <div class="cat-card">
                            <div class="left">
                                <img src="{{ asset('category_images/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid">
                            </div>
                            <div class="right">
                                <div class="cat-data">
                                    <h2>{{ $category->name }}</h2>
                                    <p>{{ $category->products_count }} Products</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>



    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Featured Products</h2>
            </div>
            <div class="row pb-3">
                @foreach ($featuredProducts as $product)
                    <div class="col-md-3">
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

                            </div>
                            <div class="card-body text-center mt-3">
                                <a class="h6 link" href="{{ route('front.show', $product->id) }}">{{ \Illuminate\Support\Str::limit($product->title, 15) }}</a>
                                <div class="price mt-2">
                                    <span class="h5"><strong>${{ $product->price }}</strong></span>
                                    @if ($product->compare_price)
                                        <span class="h6 text-underline"><del>${{ $product->compare_price }}</del></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Latest Products</h2>
        </div>
        <div class="row pb-3">
            @foreach ($latestProducts as $product)
                <div class="col-md-3">
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
                            {{-- <div class="product-action">
                                <a class="btn btn-dark" href="{{ route('front.cart.add', $product->id) }}">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                            </div> --}}
                        </div>
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="{{ route('front.show', $product->id) }}"> <a class="h6 link" href="{{ route('front.show', $product->id) }}">{{ \Illuminate\Support\Str::limit($product->title, 15) }}</a></a>
                            <div class="price mt-2">
                                <span class="h5"><strong>${{ $product->price }}</strong></span>
                                @if ($product->compare_price)
                                    <span class="h6 text-underline"><del>${{ $product->compare_price }}</del></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
</main>

<style>
    .hidden-button {
        display: none;
    }
    </style>

@endsection



