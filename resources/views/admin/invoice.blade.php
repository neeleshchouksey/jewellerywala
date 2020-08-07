@extends('admin.layouts.layout-basic')
<link rel="stylesheet" href="{{asset("public/")}}/assets/user/css/custom.css">
<style>
    .hiraola-login_btn {

        display: block;
        margin-top: 30px;
        width: 140px;
        border-radius: 0;
        height: 40px;
        line-height: 40px;
        border: 0;
        text-transform: uppercase;
        background-color: #ffde00;

    }
</style>
@section('content')
    <div class="main-content page-profile">

        <div class="page-header">
            <h3 class="page-title">Invoice</h3>

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>

                <li class="breadcrumb-item active">Invoice</li>
            </ol>
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
                                        <p><b>: </b>#{{$orders->order_id}}</p>
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
                                <p><b>{{$orders->first_name}} {{$orders->last_name}}</b></p>
                                <p><b>{{$orders->phone}}</b></p>
                                <p><b>{{$orders->email}}</b></p>
                                <p><b>{{$orders->address1}} {{$orders->address2}} {{$orders->city}} {{$orders->state}} {{$orders->pin_code}}</b></p>
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
                                    <p><i class="fas fa-rupee-sign"></i><b>{{$orders->price}}</b></p>
                                    <p><i class="fas fa-rupee-sign"></i><b>+{{$orders->shipping_charge}}</b></p>
                                    <p><i class="fas fa-rupee-sign"></i><b>-{{$orders->wallet_amount}}</b></p>
                                    <p><i class="fas fa-rupee-sign"></i><b>-{{$orders->super_coin_amount}}</b></p>
                                    <p><i class="fas fa-rupee-sign"></i><b>-{{$orders->discount}}</b></p>
                                    <div class="bt-color-in to1">
                                        <i class="fas fa-rupee-sign"></i>{{$orders->final_amount}}
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
    </div>

    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
@stop
