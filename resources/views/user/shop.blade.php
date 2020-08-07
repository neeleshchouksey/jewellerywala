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

    <input type="hidden" id="shop_id" value="{{ app('request')->input('shop_id') }}"/>
    <input type="hidden" id="sub-cat" value="{{ app('request')->input('sub_category') }}"/>
    <input type="hidden" id="key" value="{{ app('request')->input('keyword') }}"/>

    <product-filter></product-filter>

@endsection
