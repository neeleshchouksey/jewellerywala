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

                    <li class="active">Cart</li>

                </ul>

            </div>

        </div>

    </div>

   <wallet></wallet>

@endsection
<script>

</script>
