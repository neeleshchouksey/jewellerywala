@extends('user.layouts.app')

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

            <reset-password></reset-password>

        </div>

    </div>

    <!-- Hiraola's Login Register Area  End Here -->

@endsection
