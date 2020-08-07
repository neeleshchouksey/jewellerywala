@extends('user.layouts.app')

@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <h2>Shop</h2>
                <ul>
                    <li><a href="{{URL::to('/')}}">Home</a></li>
                    <li class="active">Shop</li>
                </ul>
            </div>
        </div>
    </div>

    <shop-filter></shop-filter>

@endsection
