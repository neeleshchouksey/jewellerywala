@extends('admin.layouts.layout-basic')
<?php if (Auth::user()->user_type == 0) {
    $user_type = "admin";
} else {
    $user_type = "seller";
}
?>
@section('content')
    <div class="main-content" id="dashboardPage">
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-3">
                <a class="dashbox" href="#">
                    <i class="icon-fa icon-fa-envelope text-primary"></i>
                    <span class="title">
                     {{$total_orders}}
                    </span>
                    <span class="desc">
                      New Orders
                    </span>
                </a>
            </div>
            @if($user_type=="admin")
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <a class="dashbox" href="#">
                        <i class="icon-fa icon-fa-ticket text-success"></i>
                        <span class="title">
                      {{$total_customers}}
                    </span>
                        <span class="desc">
                      Total Customers
                    </span>
                    </a>
                </div>
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <a class="dashbox" href="#">
                        <i class="icon-fa icon-fa-shopping-cart text-danger"></i>
                        <span class="title">
                      {{$total_sellers}}
                    </span>
                        <span class="desc">
                      Total Sellers
                    </span>
                    </a>
                </div>
            @else
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <a class="dashbox" href="#">
                        <i class="icon-fa icon-fa-ticket text-success"></i>
                        <span class="title">
                      {{$total_return}}
                    </span>
                        <span class="desc">
                      Total Return Requests
                    </span>
                    </a>
                </div>
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <a class="dashbox" href="#">
                        <i class="icon-fa icon-fa-shopping-cart text-danger"></i>
                        <span class="title">
                      {{$total_delivered}}
                    </span>
                        <span class="desc">
                      Total Delivered Orders
                    </span>
                    </a>
                </div>
            @endif
            <div class="col-md-12 col-lg-6 col-xl-3">
                <a class="dashbox" href="#">
                    <i class="icon-fa icon-fa-comments text-info"></i>
                    <span class="title">
                      {{$total_products}}
                    </span>
                    <span class="desc">
                      Total Products
                    </span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-xl-6 mt-2">
                <line-chart></line-chart>
            </div>
            <div class="col-lg-12 col-xl-6 mt-2">
                <bar-chart></bar-chart>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-xl-6 mt-2">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="icon-fa icon-fa-shopping-cart text-danger"></i> Recent Orders</h6>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-right"><a href="{{URL::to('/')}}/{{$user_type}}/orders">View All</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($new_orders)
                            <table class="table table-responsive">
                                <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($new_orders as $no)
                                    <tr>
                                        <td>{{$no->order_id}}</td>
                                        <td>{{$no->first_name}} {{$no->last_name}}</td>
                                        <td>{{date("d/m/Y h:iA",strtotime($no->created_at))}}</td>
                                        <td>{{$no->price}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            No Data Found
                        @endif

                    </div>
                </div>
            </div>
            @if($user_type=="admin")
                <div class="col-lg-12 col-xl-6 mt-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="icon-fa icon-fa-shopping-cart text-danger"></i> New Customers</h6>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-right"><a href="{{URL::to('/')}}/{{$user_type}}/users">View All</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($new_customers)
                                <table class="table table-responsive">
                                    <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Registered Date</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($new_customers as $nc)
                                        <tr>
                                            <td>{{$nc->first_name}} {{$nc->last_name}}</td>
                                            <td>{{date("d/m/Y",strtotime($nc->created_at))}}</td>
                                            <td>{{$nc->email}}</td>
                                            <td>{{$nc->phone}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                No Data Found
                            @endif

                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-12 col-xl-6 mt-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="icon-fa icon-fa-shopping-cart text-danger"></i> Return Requests</h6>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-right"><a href="{{URL::to('/')}}/{{$user_type}}/return-requests">View
                                            All</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($returns)
                                <table class="table table-responsive">
                                    <thead>
                                    <tr>
                                        <th>Order Id</th>
                                        <th>Customer Name</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($returns as $no)
                                        <tr>
                                            <td>{{$no->order_id}}</td>
                                            <td>{{$no->first_name}} {{$no->last_name}}</td>
                                            <td>{{date("d/m/Y h:iA",strtotime($no->created_at))}}</td>
                                            <td>{{$no->price}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                No Data Found
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop
