@extends('user.layouts.app')
<style>
    .aj-header-right_area ul li a i {
        top: 10px !important;
    }
</style>
@section('content')



    <!-- Begin Hiraola's Breadcrumb Area -->

    <div class="breadcrumb-area">

        <div class="container">

            <div class="breadcrumb-content">

                <h2>Other</h2>

                <ul>

                    <li><a href="{{URL::to('/')}}">Home</a></li>

                    <li class="active">Checkout</li>

                </ul>

            </div>

        </div>

    </div>

    <!-- Hiraola's Breadcrumb Area End Here -->
    <checkout></checkout>

@endsection
