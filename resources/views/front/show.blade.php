@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-7 pt-3 mb-3">
        <div class="container">
            <div class="row">
                <div class="col-md-5 position-relative">
                    <div id="product-carousel" class="carousel slide">
                        <div class="carousel-inner bg-light">
                            @foreach(json_decode($product->image) as $index => $image)
                                @php
                                    $color = pathinfo($image, PATHINFO_FILENAME);
                                @endphp
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" data-color="{{ $color }}">
                                    <div class="zoom-container">
                                        <img class="w-100 h-100 product-image" src="{{ asset('product_images/' . $image) }}" alt="{{ $product->title }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                            <i class="fa fa-2x fa-angle-left text-dark"></i>
                        </a>
                        <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                            <i class="fa fa-2x fa-angle-right text-dark"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-7 position-relative">
                    <div class="bg-light right">
                        <h1>{{ $product->title }}</h1>
                        <div class="d-flex mb-3">
                            <div class="text-primary mr-2">
                                <!-- Dynamic rating stars here -->
                            </div>
                            <!-- Dynamic number of reviews here -->
                        </div>
                        <div class="price mt-2">
                            <h2 class="price text-secondary"><del>${{ $product->compare_price }}</del></h2>
                            @if ($product->compare_price)
                                <h2 class="price "> ${{ $product->price }} </h2>
                            @endif
                        </div>

                        <button class="btn btn-dark addToCartBtn" data-product-id="{{ $product->id }}">
                            <i class="fa fa-shopping-cart"></i> Add To Cart
                        </button>
                    </div>

                    @if (!is_null(json_decode($product->size)) && count(json_decode($product->size)) > 0)
                        <div class="mb-3">
                            <label for="size">Size:</label>
                            <select name="size" id="size" class="form-control">
                                @foreach(json_decode($product->size) as $size)
                                    <option value="{{ $size }}">{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if (!is_null(json_decode($product->color)) && count(json_decode($product->color)) > 0)
                        <div class="mb-3">
                            <label for="color">Color:</label>
                            <select name="color" id="color" class="form-control">
                                <option value="">Select Color</option>
                                @foreach(json_decode($product->color) as $color)
                                    <option value="{{ $color }}">{{ ucfirst($color) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Zoomed Image Container -->
                    <div class="zoomed-image-container">
                        <img class="zoomed-image" src="" alt="Zoomed Image">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-5">
            <div class="bg-light">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping & Returns</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <p>
                            {!! $product->description !!} <!-- Output product description dynamically -->
                        </p>
                    </div>
                    <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sit, incidunt blanditiis suscipit quidem magnam doloribus earum hic exercitationem. Distinctio dicta veritatis alias delectus quaerat, quam sint ab nulla aperiam commodi. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sit, incidunt blanditiis suscipit quidem magnam doloribus earum hic exercitationem. Distinctio dicta veritatis alias delectus quaerat, quam sint ab nulla aperiam commodi. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sit, incidunt blanditiis suscipit quidem magnam doloribus earum hic exercitationem. Distinctio dicta veritatis alias delectus quaerat, quam sint ab nulla aperiam commodi.</p>
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

    </section>

    <section class="pt-5 section-8">
        <div class="container">
            <div class="section-title">
                <h2>Related Products</h2>
            </div>
            <div class="col-md-12">
                <div class="row">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-md-3">
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="{{ route('front.show', $relatedProduct->id) }}" class="product-img">
                                        <img class="card-img-top" src="{{ asset('product_images/' . json_decode($relatedProduct->image)[0]) }}" alt="{{ $relatedProduct->title }}">
                                    </a>
                                    <button class="btn btn-dark addToCartBtn" data-product-id="{{ $relatedProduct->id }}">
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </button>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $relatedProduct->title }}</h5>
                                    <p class="card-text">${{ $relatedProduct->price }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Include Axios via CDN -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- CSS for Zoom Effect -->
<style>
    .product-image {
        display: block;
        width: 550px;
        height: 550px;
        transition: transform 0.3s ease;
        object-fit: contain;
    }

    .zoomed-image-container {
        position: absolute;
        top: 0;
        right: auto;
        width: 700px;
        height: 1000px;
        border: 1px solid #111111;
        overflow: hidden;
        display: none;
        z-index: 10;
    }

    .zoomed-image {
        position: absolute;
        width: auto;
        height: 100%;
    }
</style>

<!-- JavaScript for Image Zoom and Container Display -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const productImages = document.querySelectorAll('.product-image');
    const colorSelect = document.getElementById('color');
    const sizeSelect = document.getElementById('size');
    const carouselInner = document.querySelector('.carousel-inner');

    function updateCarouselImages(selectedColor) {
        const items = carouselInner.querySelectorAll('.carousel-item');
        let firstVisibleItem = true;
        items.forEach(item => {
            const itemColor = item.getAttribute('data-color');
            if (itemColor.includes(selectedColor) || selectedColor === '') {
                item.style.display = 'block';
                if (firstVisibleItem) {
                    item.classList.add('active');
                    firstVisibleItem = false;
                } else {
                    item.classList.remove('active');
                }
            } else {
                item.style.display = 'none';
                item.classList.remove('active');
            }
        });
    }

    if (colorSelect) {
        colorSelect.addEventListener('change', function() {
            const selectedColor = this.value;
            updateCarouselImages(selectedColor);
        });
    }

    const addToCartBtns = document.querySelectorAll('.addToCartBtn');

    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function(event) {
            event.preventDefault();

            const productId = this.getAttribute('data-product-id');
            const selectedColor = colorSelect ? colorSelect.value : null;
            const selectedSize = sizeSelect ? sizeSelect.value : null;

            axios.post(`{{ route('front.cart.add', $product->id) }}`, {
                color: selectedColor,
                size: selectedSize
            })
            .then(function(response) {
                console.log(response.data);
                window.location.href = `{{ route('front.cart') }}`;
            })
            .catch(function(error) {
                console.error(error);
            });
        });
    });

    productImages.forEach(function(img) {
        const zoomedImgContainer = document.querySelector('.col-md-7 .zoomed-image-container');
        const zoomedImg = zoomedImgContainer.querySelector('.zoomed-image');

        if (!zoomedImgContainer) {
            console.error('Zoomed image container not found.');
            return;
        }

        img.addEventListener('mouseover', function() {
            const imgSrc = this.getAttribute('src');
            zoomedImgContainer.style.display = 'block';
            zoomedImg.setAttribute('src', imgSrc);
        });

        img.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const xPos = e.clientX - rect.left;
            const yPos = e.clientY - rect.top;

            const xPercent = xPos / rect.width;
            const yPercent = yPos / rect.height;

            const zoomedImgX = -xPercent * (zoomedImg.width - zoomedImgContainer.clientWidth);
            const zoomedImgY = -yPercent * (zoomedImg.height - zoomedImgContainer.clientHeight);

            zoomedImg.style.left = `${zoomedImgX}px`;
            zoomedImg.style.top = `${zoomedImgY}px`;
        });

        img.addEventListener('mouseout', function() {
            zoomedImgContainer.style.display = 'none';
        });
    });
});
</script>
@endsection
