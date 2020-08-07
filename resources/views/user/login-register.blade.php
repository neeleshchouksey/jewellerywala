@extends('user.layouts.app')
<style>
    .header-bottom_area .header-right_area > ul > li > a > i:nth-child(1) {

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

                    <li class="active">Login & Register</li>

                </ul>

            </div>

        </div>

    </div>

    <!-- Hiraola's Breadcrumb Area End Here -->

    <!-- Begin Hiraola's Login Register Area -->

    <div class="hiraola-login-register_area">

        <div class="container">

            <login-register></login-register>

        </div>

    </div>

    <!-- Hiraola's Login Register Area  End Here -->

@endsection
