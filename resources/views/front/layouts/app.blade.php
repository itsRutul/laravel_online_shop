<!DOCTYPE html>
<html class="no-js" lang="en_AU">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ !empty($title) ? 'Title-' . $title : 'Home' }}</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />

    <meta name="HandheldFriendly" content="True" />
    <meta name="pinterest" content="nopin" />

    <meta property="og:locale" content="en_AU" />
    <meta property="og:type" content="website" />
    <meta property="fb:admins" content="" />
    <meta property="fb:app_id" content="" />
    <meta property="og:site_name" content="" />
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="" />
    <meta property="og:image:height" content="" />
    <meta property="og:image:alt" content="" />

    <meta name="twitter:title" content="" />
    <meta name="twitter:site" content="" />
    <meta name="twitter:description" content="" />
    <meta name="twitter:image" content="" />
    <meta name="twitter:image:alt" content="" />
    <meta name="twitter:card" content="summary_large_image" />

    <link rel="stylesheet" type="text/css" href="{{ asset('front-assests/css/slick.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assests/css/slick-theme.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assests/css/video-js.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assests/css/style.css') }}?v={{ rand(111,999) }}" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

    <!-- Fav Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="#" />
</head>
<body data-instant-intensity="mousedown">

<div class="bg-light top-header">
    <div class="container">
        <div class="row align-items-center py-3 d-none d-lg-flex justify-content-between">
            <div class="col-lg-4 logo">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <span class="h1 text-uppercase text-primary bg-dark px-2">Crazy</span>
                    <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">Deals</span>
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left d-flex justify-content-end align-items-center">
                <a href="{{ url('/account/profile') }}" class="nav-link text-dark">My Account</a>
                <form action="">
                    <div class="input-group">
                        <input type="text" placeholder="Search For Products" class="form-control" aria-label="Amount (to the nearest dollar)">
                        <span class="input-group-text">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<header class="bg-dark">
    <div class="container">
        <nav class="navbar navbar-expand-xl" id="navbar">
            <a href="{{ url('/') }}" class="text-decoration-none mobile-logo">
                <span class="h2 text-uppercase text-primary bg-dark">Crazy</span>
                <span class="h2 text-uppercase text-white px-2">Deals</span>
            </a>
            <button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="navbar-toggler-icon fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                   <!-- Example in your navbar or sidebar -->

                <!-- Example in your navbar or sidebar -->

                @foreach($categories as $category)
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ $category->name }}</a>
        @if($category->subCategory->isNotEmpty())
            <ul class="dropdown-menu">
                @foreach($category->subCategory as $subCategory)
                    <li>
                        <a href="{{ route('front.shop', ['category' => $category->id, 'subcategory' => $subCategory->id]) }}" class="dropdown-item subcategory-link"
                           data-subcategory-id="{{ $subCategory->id }}"
                           data-category-id="{{ $category->id }}">
                            {{ $subCategory->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
@endforeach


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const applyFilters = () => {
            document.querySelectorAll('.subcategory-link').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    var subcategoryId = this.getAttribute('data-subcategory-id');
                    var categoryId = this.getAttribute('data-category-id');

                    // Redirect to front.shop route with category_id and subcategory_id
                    var shopUrl = this.href;
                    window.location.href = shopUrl;
                });
            });
        };

        applyFilters();
    });
</script>




                </ul>
            </div>
            <div class="right-nav py-0">
                <a href="{{ url('/cart') }}" class="ml-3 d-flex pt-2">
                    <i class="fas fa-shopping-cart text-primary"></i>
                </a>
            </div>
        </nav>
    </div>
</header>

@yield('content')

<footer class="bg-dark mt-5">
    <div class="container pb-5 pt-3">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-card">
                    <h3>Get In Touch</h3>
                    <p>No dolore ipsum accusam no lorem. <br>
                    123 Street, New York, USA <br>
                    exampl@example.com <br>
                    000 000 0000</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="footer-card">
                    <h3>Important Links</h3>
                    <ul>
                        <li><a href="{{ url('/about-us') }}" title="About">About</a></li>
                        <li><a href="{{ url('/contact-us') }}" title="Contact Us">Contact Us</a></li>
                        <li><a href="#" title="Privacy">Privacy</a></li>
                        <li><a href="#" title="Terms & Conditions">Terms & Conditions</a></li>
                        <li><a href="#" title="Refund Policy">Refund Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="footer-card">
                    <h3>My Account</h3>
                    <ul>
                        <li><a href="#" title="Login">Login</a></li>
                        <li><a href="#" title="Register">Register</a></li>
                        <li><a href="#" title="My Orders">My Orders</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="copy-right text-center">
                        <p>© Copyright till the end of world Crazy Deals. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </footer>
    <script src="{{ asset('front-assests/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('front-assests/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
    <script src="{{ asset('front-assests/js/instantpages.5.1.0.min.js') }}"></script>
    <script src="{{ asset('front-assests/js/lazyload.17.6.0.min.js') }}"></script>
    <script src="{{ asset('front-assests/js/slick.min.js') }}"></script>
    <script src="{{ asset('front-assests/js/custom.js') }}"></script>
    <script>
        window.onscroll = function() {myFunction()};

        var navbar = document.getElementById("navbar");
        var sticky = navbar.offsetTop;

        function myFunction() {
            if (window.pageYOffset >= sticky) {
                navbar.classList.add("sticky")
            } else {
                navbar.classList.remove("sticky");
            }
        }
    </script>
    @yield('customjs')
    </body>
    </html>

