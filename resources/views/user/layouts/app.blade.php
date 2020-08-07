<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Jewellerywala.com</title>
    <meta name="robots" content="noindex, follow"/>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset("public/")}}/assets/user/images/favicon.ico">

    <!-- CSS
	============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/vendor/bootstrap.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/vendor/font-awesome.css">
    <!-- Fontawesome Star -->
    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/vendor/fontawesome-stars.css">
    <!-- Ion Icon -->
    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/vendor/ion-fonts.css">
    <!-- Slick CSS -->
    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/plugins/slick.css">
    <!-- Animation -->
    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/plugins/animate.css">
    <!-- jQuery Ui -->
    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/plugins/jquery-ui.min.css">
    <!-- Lightgallery -->
    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/plugins/lightgallery.min.css">
    <!-- Nice Select -->
    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/plugins/nice-select.css">
    <!-- Timecircles -->
    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/plugins/timecircles.css">
    <link rel=stylesheet href='./node_modules/nouislider/distribute/nouislider.min.css' media=all />

    <link rel="stylesheet" href="../node_modules/drift-zoom/dist/drift-basic.min.css"/>

    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/custom.css">

    <!-- Vendor & Plugins CSS (Please remove the comment from below vendor.min.css & plugins.min.css for better website load performance and remove css files from the above) -->
<!--
    <script src="{{asset("public/")}}/assets/user/js/vendor/vendor.min.js"></script>
    <script src="{{asset("public/")}}/assets/user/js/plugins/plugins.min.js"></script>
    -->

    <!-- Main Style CSS (Please use minify version for better website load performance) -->
    <link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/style.css">
<!--<link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/style.min.css">-->

    <script>
        var APP_URL = "{{URL::to('/')}}";
        var AUTH_USER = "{{Auth::user()}}";
        var USER_TYPE = "user";
        var AUTH_USER_ID = "";
        @if(Auth::user())
            AUTH_USER_ID = "{{Auth::user()->id}}";
        @endif
    </script>

</head>

<body class="template-color-1">

<div class="main-wrapper"  id="user-app">
    <?php $category = get_categories();
    ?>
    <div >
        <!-- Begin Loading Area -->
        <div class="loading">
            <div class="text-center middle">
                <div class="lds-ellipsis">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <!-- Begin Hiraola's Header Main Area -->
        <header class="header-main_area">
            <div class="header-top_area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="ht-left_area">
                                <div class="header-shipping_area">
                                    <ul>
                                        <li>
                                            <span>Telephone Enquiry:</span>
                                            <a href="callto://+123123321345">(+123) 123 321 345</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="ht-right_area">
                                <div class="ht-menu">
                                    <ul>
                                        <?php $mp = get_all_metal_price(); ?>

                                        <li><a href="#">Metal Rates<i
                                                        class="fa fa-chevron-down"></i></a>
                                            <ul class="ht-dropdown ht-my_account">
                                                @foreach($mp as $m)
                                                <li><a href="javascript:void(0)">{{$m->category_name}} {{$m->purity_name}} - <i class="fa fa-rupee-sign"></i> {{$m->price}}</a></li>
                                                    @endforeach
                                            </ul>
                                        </li>
                                        @if(Auth::user())
                                            <li><a href="#">{{Auth::user()->first_name}} {{Auth::user()->last_name}}<i
                                                            class="fa fa-chevron-down"></i></a>
                                                <ul class="ht-dropdown ht-my_account">
                                                    <li><a href="{{URL::to('/')}}/my-account">My Account</a></li>
                                                    <li><a href="{{URL::to('/')}}/wallet">My Wallet</a></li>
                                                    <li><a href="{{URL::to('/')}}/my-cart">My Cart</a></li>
                                                    <li><a href="{{URL::to('/')}}/my-wishlist">My Wishlist</a></li>
                                                    <li><a href="{{URL::to('/')}}/logout">Logout</a></li>
                                                </ul>
                                            </li>
                                        @else
                                            <li class="active"><a href="{{URL::to('/')}}/login-register">User Login
                                                    | Register</a></li>
                                            <li class="active"><a href="{{URL::to('/')}}/seller/login-register">Seller
                                                    Login | Register</a></li>

                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-middle_area d-none d-lg-block">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="header-logo1">
                                <a href="{{URL::to('/')}}">
                                    <img src="{{asset("public/")}}/assets/user/final_logo.png" alt="Hiraola's  Logo"
                                         style="">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="hm-form_area">
                                <form action="{{URL::to('/')}}/shop" class="hm-searchbox">
                                    <select class="nice-select select-search-category"  name="sub_category" id="sub_category">
                                        <option value="">ALL</option>
                                        @foreach($category as $c)
                                            <option value="{{$c->id}}" disabled>{{$c->category_name}}</option>
                                            @foreach($c->subcategory as $s)
                                                <option value="{{$s->id}}">--{{$s->sub_category_name}}--</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <input type="text" name="keyword" placeholder="Search by product name..." id="keyword">
                                    <button class="li-btn" type="submit"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <mini-cart></mini-cart>
            <div class="mobile-menu_wrapper" id="mobileMenu">
                <div class="offcanvas-menu-inner">
                    <div class="container">
                        <a href="#" class="btn-close"><i class="ion-android-close"></i></a>
                        <div class="offcanvas-inner_search">
                            <form action="#" class="hm-searchbox">
                                <input type="text" placeholder="Search for item...">
                                <button class="search_btn" type="submit"><i class="ion-ios-search-strong"></i></button>
                            </form>
                        </div>
                        <nav class="offcanvas-navigation">
                            <ul class="mobile-menu">

                                <li>
                                    <a href="{{URL::to('/')}}">
                                        <span class="mm-text">Home</span>
                                    </a>
                                </li>
                                @foreach($category as $c)
                                    <li class="menu-item-has-children active" ><a href="javascript:void(0);"><span class="mm-text">{{$c->category_name}}</span></a>
                                        <ul class="sub-menu">
                                            @foreach($c->subcategory as $s)
                                                <li>
                                                    <a href="{{URL::to('/')}}/shop?sub_category={{$s->id}}">
                                                        <span class="mm-text">{{$s->sub_category_name}}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="mm-text">Contact</span>
                                    </a>
                                </li>

                                    @if(Auth::user())
                                        <li><a href="#">{{Auth::user()->first_name}} {{Auth::user()->last_name}}<i
                                                        class="fa fa-chevron-down"></i></a>
                                            <ul class="ht-dropdown ht-my_account">
                                                <li><a href="{{URL::to('/')}}/my-account">My Account</a></li>
                                                <li><a href="{{URL::to('/')}}/wallet">My Wallet</a></li>
                                                <li><a href="{{URL::to('/')}}/my-cart">My Cart</a></li>
                                                <li><a href="{{URL::to('/')}}/my-wishlist">My Wishlist</a></li>
                                                <li><a href="{{URL::to('/')}}/logout">Logout</a></li>
                                            </ul>
                                        </li>
                                    @else
                                        <li class="active"><a href="{{URL::to('/')}}/login-register">User Login
                                                | Register</a></li>
                                        <li class="active"><a href="{{URL::to('/')}}/seller/login-register">Seller
                                                Login | Register</a></li>

                                    @endif

                            </ul>
                        </nav>

                    </div>
                </div>
            </div>

        </header>
        <!-- Hiraola's Header Main Area End Here -->


        <div>
            @yield('content')

        </div>


        <!-- Begin Hiraola's Shipping Area -->
        <div class="hiraola-shipping_area">
            <div class="container">
                <div class="shipping-nav">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="shipping-item">
                                <div class="shipping-icon">
                                    <img src="{{asset("public/")}}/assets/user/images/shipping-icon/1.png"
                                         alt="Hiraola's Shipping Icon">
                                </div>
                                <div class="shipping-content">
                                    <h6>Free Uk Standard Delivery</h6>
                                    <p>Designated day delivery</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="shipping-item">
                                <div class="shipping-icon">
                                    <img src="{{asset("public/")}}/assets/user/images/shipping-icon/2.png"
                                         alt="Hiraola's Shipping Icon">
                                </div>
                                <div class="shipping-content">
                                    <h6>Freshyly Prepared Ingredients</h6>
                                    <p>Made for your delivery date</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="shipping-item">
                                <div class="shipping-icon">
                                    <img src="{{asset("public/")}}/assets/user/images/shipping-icon/3.png"
                                         alt="Hiraola's Shipping Icon">
                                </div>
                                <div class="shipping-content">
                                    <h6>98% Of Anta Clients</h6>
                                    <p>Reach their personal goals set</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="shipping-item">
                                <div class="shipping-icon">
                                    <img src="{{asset("public/")}}/assets/user/images/shipping-icon/4.png"
                                         alt="Hiraola's Shipping Icon">
                                </div>
                                <div class="shipping-content">
                                    <h6>Winner Of 15 Awards</h6>
                                    <p>Healthy food and drink 2019</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hiraola's Shipping Area End Here -->

        <!-- Begin Hiraola's Footer Area -->
        <div class="hiraola-footer_area">
            <div class="footer-top_area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="footer-widgets_info">

                                <div class="footer-widgets_logo">
                                    <a href="{{URL::to('/')}}">
                                        <img src="{{asset("public/")}}/assets/user/final_logo.png"
                                             alt="Hiraola's  Logo" style="width: 300px; height: 130px;">
                                    </a>
                                </div>

                                <div class="widget-short_desc">
                                    <p>We are a team of designers and developers that create high quality HTML Template &
                                        Woocommerce, Shopify Theme.
                                    </p>
                                </div>
                                <div class="hiraola-social_link aj-hiraola-social_link">
                                    <ul>
                                        <li class="facebook">
                                            <a href="https://www.facebook.com/" data-toggle="tooltip" target="_blank"
                                               title="Facebook">
                                                <i class="fab fa-facebook"></i>
                                            </a>
                                        </li>
                                        <li class="twitter">
                                            <a href="https://twitter.com/" data-toggle="tooltip" target="_blank"
                                               title="Twitter">
                                                <i class="fab fa-twitter-square"></i>
                                            </a>
                                        </li>
                                        <li class="google-plus">
                                            <a href="https://www.plus.google.com/discover" data-toggle="tooltip"
                                               target="_blank" title="Google Plus">
                                                <i class="fab fa-google-plus"></i>
                                            </a>
                                        </li>
                                        <li class="instagram">
                                            <a href="https://rss.com/" data-toggle="tooltip" target="_blank"
                                               title="Instagram">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="footer-widgets_area">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="footer-widgets_title">
                                            <h6>Product</h6>
                                        </div>
                                        <div class="footer-widgets">
                                            <ul>
                                                <li><a href="#">Prices drop</a></li>
                                                <li><a href="#">New products</a></li>
                                                <li><a href="#">Best sales</a></li>
                                                <li><a href="#">Contact us</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="footer-widgets_info">
                                            <div class="footer-widgets_title">
                                                <h6>About Us</h6>
                                            </div>
                                            <div class="widgets-essential_stuff">
                                                <ul>
                                                    <li class="hiraola-address"><i class="ion-ios-location"></i><span>Address:</span>
                                                        The Barn, Ullenhall, Henley
                                                        in
                                                        Arden B578 5CC, England
                                                    </li>
                                                    <li class="hiraola-phone"><i class="ion-ios-telephone"></i><span>Call Us:</span>
                                                        <a href="tel://+123123321345">+123 321 345</a>
                                                    </li>
                                                    <li class="hiraola-email"><i
                                                                class="ion-android-mail"></i><span>Email:</span> <a
                                                                href="mailto://info@yourdomain.com">info@yourdomain.com</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="instagram-container footer-widgets_area">
                                            <div class="footer-widgets_title">
                                                <h6>Sign Up For Newslatter</h6>
                                            </div>
                                            <div class="widget-short_desc">
                                                <p>Subscribe to our newsletters now and stay up-to-date with new
                                                    collections</p>
                                            </div>
                                            <div class="newsletter-form_wrap">
                                                <form action="http://devitems.us11.list-manage.com/subscribe/post?u=6bbb9b6f5827bd842d9640c82&amp;id=05d85f18ef"
                                                      method="post" id="mc-embedded-subscribe-form"
                                                      name="mc-embedded-subscribe-form" class="newsletters-form validate"
                                                      target="_blank" novalidate>
                                                    <div id="mc_embed_signup_scroll">
                                                        <div id="mc-form" class="mc-form subscribe-form">
                                                            <input id="mc-email" class="newsletter-input" type="email"
                                                                   autocomplete="off" placeholder="Enter your email"/>
                                                            <button class="newsletter-btn" id="mc-submit">
                                                                <i class="ion-android-mail" aria-hidden="true"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6 text-right">
                                        <div class="footer-widgets_title">
                                            <h6>Terms and Conditions</h6>
                                        </div>
                                        <div class="footer-widgets">
                                            <ul>
                                                <li><a href="{{URL::to('/')}}/terms-and-conditions-buyer">Terms and Conditions Buyer</a></li>
                                                <li><a href="{{URL::to('/')}}/terms-and-conditions-seller">Terms and Conditions Seller</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom_area">
                <div class="container">
                    <div class="footer-bottom_nav">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="footer-links">
                                    <ul>
                                        <li><a href="#">Online Shopping</a></li>
                                        <li><a href="#">Promotions</a></li>
                                        <li><a href="#">My Orders</a></li>
                                        <li><a href="#">Help</a></li>
                                        <li><a href="#">Customer Service</a></li>
                                        <li><a href="#">Support</a></li>
                                        <li><a href="#">Most Populars</a></li>
                                        <li><a href="#">New Arrivals</a></li>
                                        <li><a href="#">Special Products</a></li>
                                        <li><a href="#">Manufacturers</a></li>
                                        <li><a href="#">Our Stores</a></li>
                                        <li><a href="#">Shipping</a></li>
                                        <li><a href="#">Payments</a></li>
                                        <li><a href="#">Warantee</a></li>
                                        <li><a href="#">Refunds</a></li>
                                        <li><a href="#">Checkout</a></li>
                                        <li><a href="#">Discount</a></li>
                                        <li><a href="#">Refunds</a></li>
                                        <li><a href="#">Policy Shipping</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="payment">
                                    <a href="#">
                                        <img src="{{asset("public/")}}/assets/user/images/footer/payment/1.png"
                                             alt="Hiraola's Payment Method">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="copyright">
                                    <span>Copyright &copy; 2019 <a href="#">Jewellerywala.</a> All rights reserved.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hiraola's Footer Area End Here -->
        <!-- Begin Hiraola's Modal Area -->
        <div class="modal fade modal-wrapper" id="exampleModalCenter">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="modal-inner-area sp-area row">
                            <div class="col-lg-5 col-md-5">
                                <div class="sp-img_area">
                                    <div class="sp-img_slider-2 slick-img-slider hiraola-slick-slider arrow-type-two"
                                         data-slick-options='{
                                                        "slidesToShow": 1,
                                                        "arrows": false,
                                                        "fade": true,
                                                        "draggable": false,
                                                        "swipe": false,
                                                        "asNavFor": ".sp-img_slider-nav"
                                                        }'>
                                        <div class="single-slide red">
                                            <img src="{{asset("public/")}}/assets/user/images/single-product/large-size/1.jpg"
                                                 alt="Hiraola's Product Image">
                                        </div>
                                        <div class="single-slide orange">
                                            <img src="{{asset("public/")}}/assets/user/images/single-product/large-size/2.jpg"
                                                 alt="Hiraola's Product Image">
                                        </div>
                                        <div class="single-slide brown">
                                            <img src="{{asset("public/")}}/assets/user/images/single-product/large-size/3.jpg"
                                                 alt="Hiraola's Product Image">
                                        </div>
                                        <div class="single-slide umber">
                                            <img src="{{asset("public/")}}/assets/user/images/single-product/large-size/4.jpg"
                                                 alt="Hiraola's Product Image">
                                        </div>
                                    </div>
                                    <div class="sp-img_slider-nav slick-slider-nav hiraola-slick-slider arrow-type-two"
                                         data-slick-options='{
                                   "slidesToShow": 4,
                                    "asNavFor": ".sp-img_slider-2",
                                   "focusOnSelect": true
                                  }' data-slick-responsive='[
                                                        {"breakpoint":1201, "settings": {"slidesToShow": 2}},
                                                        {"breakpoint":768, "settings": {"slidesToShow": 3}},
                                                        {"breakpoint":577, "settings": {"slidesToShow": 3}},
                                                        {"breakpoint":481, "settings": {"slidesToShow": 2}},
                                                        {"breakpoint":321, "settings": {"slidesToShow": 2}}
                                                    ]'>
                                        <div class="single-slide red">
                                            <img src="{{asset("public/")}}/assets/user/images/single-product/small-size/1.jpg"
                                                 alt="Hiraola's Product Thumnail">
                                        </div>
                                        <div class="single-slide orange">
                                            <img src="{{asset("public/")}}/assets/user/images/single-product/small-size/2.jpg"
                                                 alt="Hiraola's Product Thumnail">
                                        </div>
                                        <div class="single-slide brown">
                                            <img src="{{asset("public/")}}/assets/user/images/single-product/small-size/3.jpg"
                                                 alt="Hiraola's Product Thumnail">
                                        </div>
                                        <div class="single-slide umber">
                                            <img src="{{asset("public/")}}/assets/user/images/single-product/small-size/4.jpg"
                                                 alt="Hiraola's Product Thumnail">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-7 col-lg-6 col-md-6">
                                <div class="sp-content">
                                    <div class="sp-heading">
                                        <h5><a href="#">Light Inverted Pendant Quis Justo Condimentum</a></h5>
                                    </div>
                                    <div class="rating-box">
                                        <ul>
                                            <li><i class="fa fa-star-of-david"></i></li>
                                            <li><i class="fa fa-star-of-david"></i></li>
                                            <li><i class="fa fa-star-of-david"></i></li>
                                            <li><i class="fa fa-star-of-david"></i></li>
                                            <li><i class="fa fa-star-of-david"></i></li>
                                        </ul>
                                    </div>
                                    <div class="price-box">
                                        <span class="new-price">£82.84</span>
                                        <span class="old-price">£93.68</span>
                                    </div>
                                    <div class="essential_stuff">
                                        <ul>
                                            <li>EX Tax:<span>£453.35</span></li>
                                            <li>Price in reward points:<span>400</span></li>
                                        </ul>
                                    </div>
                                    <div class="list-item">
                                        <ul>
                                            <li>10 or more £81.03</li>
                                            <li>20 or more £71.09</li>
                                            <li>30 or more £61.15</li>
                                        </ul>
                                    </div>
                                    <div class="list-item last-child">
                                        <ul>
                                            <li>Brand<a href="javascript:void(0)">Buxton</a></li>
                                            <li>Product Code: Product 15</li>
                                            <li>Reward Points: 100</li>
                                            <li>Availability: In Stock</li>
                                        </ul>
                                    </div>
                                    <div class="color-list_area">
                                        <div class="color-list_heading">
                                            <h4>Available Options</h4>
                                        </div>
                                        <span class="sub-title">Color</span>
                                        <div class="color-list">
                                            <a href="javascript:void(0)" class="single-color active"
                                               data-swatch-color="red">
                                                <span class="bg-red_color"></span>
                                                <span class="color-text">Red (+£3.61)</span>
                                            </a>
                                            <a href="javascript:void(0)" class="single-color" data-swatch-color="orange">
                                                <span class="burnt-orange_color"></span>
                                                <span class="color-text">Orange (+£2.71)</span>
                                            </a>
                                            <a href="javascript:void(0)" class="single-color" data-swatch-color="brown">
                                                <span class="brown_color"></span>
                                                <span class="color-text">Brown (+£0.90)</span>
                                            </a>
                                            <a href="javascript:void(0)" class="single-color" data-swatch-color="umber">
                                                <span class="raw-umber_color"></span>
                                                <span class="color-text">Umber (+£1.81)</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="quantity">
                                        <label>Quantity</label>
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box" value="1" type="text">
                                            <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
                                            <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="hiraola-group_btn">
                                        <ul>
                                            <li><a href="cart.html" class="add-to_cart">Cart To Cart</a></li>
                                            <li><a href="cart.html"><i class="ion-android-favorite-outline"></i></a></li>
                                            <li><a href="cart.html"><i class="ion-ios-shuffle-strong"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="hiraola-tag-line">
                                        <h6>Tags:</h6>
                                        <a href="javascript:void(0)">Ring</a>,
                                        <a href="javascript:void(0)">Necklaces</a>,
                                        <a href="javascript:void(0)">Braid</a>
                                    </div>
                                    <div class="hiraola-social_link aj-hiraola-social_link">
                                        <ul>
                                            <li class="facebook">
                                                <a href="https://www.facebook.com/" data-toggle="tooltip" target="_blank"
                                                   title="Facebook">
                                                    <i class="fab fa-facebook"></i>
                                                </a>
                                            </li>
                                            <li class="twitter">
                                                <a href="https://twitter.com/" data-toggle="tooltip" target="_blank"
                                                   title="Twitter">
                                                    <i class="fab fa-twitter-square"></i>
                                                </a>
                                            </li>
                                            <li class="youtube">
                                                <a href="https://www.youtube.com/" data-toggle="tooltip" target="_blank"
                                                   title="Youtube">
                                                    <i class="fab fa-youtube"></i>
                                                </a>
                                            </li>
                                            <li class="google-plus">
                                                <a href="https://www.plus.google.com/discover" data-toggle="tooltip"
                                                   target="_blank" title="Google Plus">
                                                    <i class="fab fa-google-plus"></i>
                                                </a>
                                            </li>
                                            <li class="instagram">
                                                <a href="https://rss.com/" data-toggle="tooltip" target="_blank"
                                                   title="Instagram">
                                                    <i class="fab fa-instagram"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hiraola's Modal Area End Here -->
    </div>
</div>

<!-- JS
============================================ -->

<!-- jQuery JS -->
<script src="{{asset("public/")}}/assets/user/js/vendor/jquery-1.12.4.min.js"></script>
<!-- Modernizer JS -->
<script src="{{asset("public/")}}/assets/user/js/vendor/modernizr-2.8.3.min.js"></script>
<!-- Popper JS -->
<script src="{{asset("public/")}}/assets/user/js/vendor/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="{{asset("public/")}}/assets/user/js/vendor/bootstrap.min.js"></script>

<!-- Slick Slider JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/slick.min.js"></script>
<!-- Countdown JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/countdown.js"></script>
<!-- Barrating JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/jquery.barrating.min.js"></script>
<!-- Counterup JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/jquery.counterup.js"></script>
<!-- Nice Select JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/jquery.nice-select.js"></script>
<!-- Sticky Sidebar JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/jquery.sticky-sidebar.js"></script>
<!-- Jquery-ui JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/jquery-ui.min.js"></script>
<script src="{{asset("public/")}}/assets/user/js/plugins/jquery.ui.touch-punch.min.js"></script>
<!-- Lightgallery JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/lightgallery.min.js"></script>
<!-- Scroll Top JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/scroll-top.js"></script>
<!-- Theia Sticky Sidebar JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/theia-sticky-sidebar.min.js"></script>
<!-- Waypoints JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/waypoints.min.js"></script>
<!-- Instafeed JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/instafeed.min.js"></script>
<!-- ElevateZoom JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/jquery.elevateZoom-3.0.8.min.js"></script>
<!-- Timecircles JS -->
<script src="{{asset("public/")}}/assets/user/js/plugins/timecircles.js"></script>

<!-- Vendor & Plugins JS (Please remove the comment from below vendor.min.js & plugins.min.js for better website load performance and remove js files from avobe) -->
<!--
<script src="{{asset("public/")}}/assets/user/js/vendor/vendor.min.js"></script>
<script src="{{asset("public/")}}/assets/user/js/plugins/plugins.min.js"></script>
-->
<script src='./node_modules/nouislider/distribute/nouislider.min.js'></script>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src='../node_modules/drift-zoom/dist/Drift.min.js'></script>
<!-- Main JS -->
<script src="{{asset("public/")}}/assets/user/js/main.js"></script>

<script src="{{asset('/public/assets/admin/js/core/app.js')}}"></script>
<script>
    $('.brand-slider').slick({
        infinite: true,
        arrows: false,
        dots: false,
        speed: 2000,
        slidesToShow: 5,
        slidesToScroll: 1,
        prevArrow: '<button class="slick-prev"><i class="ion-ios-arrow-back"></i></button>',
        nextArrow: '<button class="slick-next"><i class="ion-ios-arrow-forward"></i></button>',
        responsive: [{
            breakpoint: 1199,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1
            }
        },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }
        ]
    });

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
</body>


</html>
