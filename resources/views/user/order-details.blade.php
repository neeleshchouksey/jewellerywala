@extends('user.layouts.app')

@section('content')


        <!-- Begin Hiraola's Breadcrumb Area -->

        <div class="breadcrumb-area">

            <div class="container">

                <div class="breadcrumb-content">

                    <h2>Other</h2>

                    <ul>

                        <li><a href="{{URL::to('/')}}">Home</a></li>

                        <li class="active">Order Details</li>

                    </ul>

                </div>

            </div>

        </div>

        <!-- Hiraola's Breadcrumb Area End Here -->

        <!-- Begin Hiraola's Page Content Area -->

        <main class="page-content">

            <!-- Begin Hiraola's Account Page Area -->

            <div class="account-page-area">


                <div class="myaccount-orders mt-5">

                    <h5 class="mb-3"><b>Order Id: {{$order_id}} </b></h5>

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead>
                            <tr>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $o)
                                <tr>
                                    <td><img height="70" width="70" src="{{URL::to('/')}}/public/storage/product-images/{{$o->front_image}}"></td>
                                    <td>{{$o->product_name}}</td>
                                    <td>{{$o->quantity}}</td>
                                    <td>{{$o->unit_price}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>

            </div>

            <!-- Hiraola's Account Page Area End Here -->

        </main>

        <!-- Hiraola's Page Content Area End Here -->

        <!-- Begin Hiraola's Footer Area -->
@endsection
