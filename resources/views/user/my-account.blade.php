@extends('user.layouts.app')
<style>

    .header-bottom_area .header-right_area > ul > li > a > i {
        font-size: 26px;
        position: relative;
        top: 10px;
    }
    .aj-hiraola-social_link ul li a i {
        position: relative;
        top: 10px;
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

                        <li class="active">My Account</li>

                    </ul>

                </div>

            </div>

        </div>

        <!-- Hiraola's Breadcrumb Area End Here -->

        <!-- Begin Hiraola's Page Content Area -->

        <main class="page-content">

            <!-- Begin Hiraola's Account Page Area -->

            <div class="account-page-area">


               <my-account></my-account>

            </div>

            <!-- Hiraola's Account Page Area End Here -->

        </main>

        <!-- Hiraola's Page Content Area End Here -->

        <!-- Begin Hiraola's Footer Area -->
@endsection
