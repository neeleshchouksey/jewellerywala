@extends('user.layouts.app')

@section('content')


    <!-- Begin Hiraola's Breadcrumb Area -->

    <div class="breadcrumb-area">

        <div class="container">

            <div class="breadcrumb-content">

                <h2>Other</h2>

                <ul>

                    <li><a href="{{URL::to('/')}}">Home</a></li>

                    <li class="active">Invoice</li>

                </ul>

            </div>

        </div>

    </div>
    <div class="invoice-form">
        <page id="divName" size="A4" style="background-image: url('{{asset("/public")}}/assets/user/images/banner/bottom.png');background-repeat: no-repeat;background-position: bottom right;">
            <div class="invoice-box">
                <div class="image-top">
                    <img src="{{asset("/public")}}/assets/user/images/banner/1.png">
                </div>
                <div class="invo-boc-had">

                    <div class="new-sec-con1">
                        <div class="invo-img">
                            <img src="{{asset("/public")}}/assets/user/final_logo.png">
                        </div>
                        <div class="content-1">
                            <h1>Invoice</h1>
                            <div class="content-id">
                                <div class="name-cont">
                                    <p><b>Invoice No</b></p>
                                    <p><b>Invoice Date</b></p>
{{--                                    <p><b>Issue Date</b></p>--}}
                                </div>
                                <div class="detal-con">
                                    <p><b>: </b>#{{$order->order_id}}</p>
                                    <p><b>: </b>{{date("d/m/Y h:i:A")}}</p>
{{--                                    <p><b>: </b>01/01/2020</p>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sec-tebel-nsho">
                        <div class="text-tabal-vier">
                            <div class="p-name-i1">Product Name</div>
                            <div class="p-name-i2">Quantity</div>
                            <div class="p-name-i3">Price</div>
                            <div class="p-name-i4">Total</div>
                        </div>

                        @foreach($order_item as $oi)

                        <div class="text-tabal-content">
                            <div class="text-contem-in1">{{$oi->product_name}}</div>
                            <div class="text-contem-in2">{{$oi->quantity}}</div>
                            <div class="text-contem-in3">{{$oi->unit_price}}</div>
                            <div class="text-contem-in4">{{$oi->quantity * $oi->unit_price}}</div>
                        </div>
                            @endforeach
                    </div>
                    <div class="footer-detal-in-1">
                        <div class="deteal-contenmt">
                            <h2>Invoice to :</h2>
                            <p><b>{{$order->first_name}} {{$order->last_name}}</b></p>
                            <p><b>{{$order->phone}}</b></p>
                            <p><b>{{$order->email}}</b></p>
                            <p><b>{{$order->address1}} {{$order->address2}} {{$order->city}} {{$order->state}} {{$order->pin_code}}</b></p>
                            <h2 class="pad-14">Invoice Form :</h2>
                            <p><b>Jewellery-Wala</b></p>
                            <p><b>+919876543210</b></p>
                            <p><b>jewellerywala@gmail.com</b></p>
                            <p><b>GSTN : </b>21JJJJJ0000J1Z5</p>
                        </div>

                        <div class="deteal-contenmt1">
                            <div class="footet-total">
                                <p><b>Order Total</b></p>
                                <p><b>Shipping Charges</b></p>
                                <p><b>Wallet Amount</b></p>
                                <p><b>Cashback Money</b></p>
                                <p><b>Coupon Code Discount</b></p>
                                <div class="bt-color-in">
                                    TOTAL
                                </div>
                            </div>
                            <div class="footet-total2">
                                <p><i class="fas fa-rupee-sign"></i><b>{{$order->price}}</b></p>
                                <p><i class="fas fa-rupee-sign"></i><b>+{{$order->shipping_charge}}</b></p>
                                <p><i class="fas fa-rupee-sign"></i><b>-{{$order->wallet_amount}}</b></p>
                                <p><i class="fas fa-rupee-sign"></i><b>-{{$order->super_coin_amount}}</b></p>
                                <p><i class="fas fa-rupee-sign"></i><b>-{{$order->discount}}</b></p>
                                <div class="bt-color-in to1">
                                    <i class="fas fa-rupee-sign"></i>{{$order->final_amount}}
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
                <div class="footer-last-a">
                    <div class="footer-last-b01">
                        <div class="terms-see">
                            <h5>Term & Conditions</h5>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                has been the industry's standard dummy text ever since the 1500s, when an unknown
                                printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                    </div>

                </div>


            </div>
        </page>
        <a href="JavaScript:void(0);" onclick="printDiv('divName')" class="text-center hiraola-login_btn"
           title="Invoices">Print</a>
    </div>
    <!-- Hiraola's Breadcrumb Area End Here -->

    <!-- Begin Hiraola's Cart Area -->

    <!-- Hiraola's Cart Area End Here -->

    <!-- Begin Hiraola's Footer Area -->

@endsection
