@extends('user.layouts.app')
<style>

</style>
@section('content')

    <!-- Begin Hiraola's Breadcrumb Area -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <h2>Single Product Type</h2>
                <ul>
                    <li><a href="{{URL::to('/')}}">Home</a></li>
                    <li class="active">Single Product</li>
                </ul>
            </div>
        </div>
    </div>
    <product-details></product-details>




@endsection
