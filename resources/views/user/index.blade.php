@extends('user.layouts.app')
<style xmlns:v-on="http://www.w3.org/1999/xhtml">
    .checked {
        color: #ffd055;
    }

    .hiraola-social_link > ul > li > a i {
        position: relative;
        top: 10px;
    }
</style>
@section('content')
    <div xmlns:v-on="http://www.w3.org/1999/xhtml">
        <div class="hiraola-slider_area-2">
            <div class="main-slider">
                <!-- Begin Single Slide Area -->
                @foreach($section1 as $s1)
                    <div class="single-slide animation-style-01"
                         style='background-image: url("{{URL::to('/')}}/public/storage/homepage-section-images/{{$s1->image}}");background-repeat: no-repeat;background-position: center center;background-size: cover;min-height: 320px;'>
                        <div class="container">
                            <div class="slider-content">
                                <h5>{{$s1->text1}}</h5>
                                <h2>{{$s1->text2}}</h2>
                                <h3>{{$s1->text3}}</h3>
                                <h4>{{$s1->text4}}</h4>
                                <div class="hiraola-btn-ps_center slide-btn">
                                    <a class="hiraola-btn" href="{{$s1->link}}">Shopping Now</a>
                                </div>
                            </div>
                            <div class="slider-progress"></div>
                        </div>
                    </div>
            @endforeach
            <!-- Single Slide Area End Here -->
            </div>
        </div>

        <div class="hiraola-banner_area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="banner-item">
                            <a href="{{$section2->link1}}">
                                <img class=""
                                     src="{{URL::to('/')}}/public/storage/homepage-section-images/{{$section2->image1}}"
                                     alt="Hiraola's Banner">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="banner-item">
                            <a href="{{$section2->link2}}">
                                <img class=""
                                     src="{{URL::to('/')}}/public/storage/homepage-section-images/{{$section2->image2}}"
                                     alt="Hiraola's Banner">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="banner-item">
                            <a href="{{$section2->link3}}">
                                <img class=""
                                     src="{{URL::to('/')}}/public/storage/homepage-section-images/{{$section2->image3}}"
                                     alt="Hiraola's Banner">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Begin Hiraola's Product Area -->
        <new-arrival-slider></new-arrival-slider>
        <!-- Hiraola's Product Area End Here -->

        <div class="static-banner_area">
            <div class="new-chang">
                <div class="col-lg-12 new-chang1">
                    <div class="image-chang"
                         style='background-image: url("{{URL::to('/')}}/public/storage/homepage-section-images/{{$section4->image}}"); background-size: cover; min-height: 345px; background-repeat: no-repeat; background-position: center;'></div>
                    <div class="static-banner-content">
                        <p>{{$section4->text1}}</p>
                        <h2>{{$section4->text2}}</h2>
                        <h3>{{$section4->text3}}</h3>
                        <p class="schedule">
                            {{$section4->text4}}
                        </p>
                        <div class="hiraola-btn-ps_left">
                            <a href="{{$section4->link}}" class="hiraola-btn">Shopping Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <featured-slider></featured-slider>

        <div class="hiraola-banner_area-2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="banner-item">
                            <a href="{{$section6->link1}}">
                                <img class=""
                                     src="{{URL::to('/')}}/public/storage/homepage-section-images/{{$section6->image1}}"
                                     alt="Hiraola's Banner">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="banner-item">
                            <a href="{{$section6->link2}}">
                                <img class=""
                                     src="{{URL::to('/')}}/public/storage/homepage-section-images/{{$section6->image2}}"
                                     alt="Hiraola's Banner">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <trending-slider></trending-slider>


        <div class="brand-area">
            <div class="container">
                <div class="brand-slider_nav">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="brand-slider">
                                @foreach($shop_logo as $sl)
                                <div class="slide-item">
                                    <a href="{{URL::to('/')}}/shop?shop_id={{$sl->user_id}}">
                                        <img width="174" height="106" src="{{asset("public/storage/shop-images/")}}/{{$sl->logo}}">
                                    </a>
                                </div>
                                    @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
