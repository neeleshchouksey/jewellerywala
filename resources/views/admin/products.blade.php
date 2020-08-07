@extends('admin.layouts.layout-basic')

@section('content')
    <div class="main-content page-profile">
        <div class="page-header">
            <h3 class="page-title"><i class="icon-fa icon-fa-product-hunt"></i> Manage Products</h3>

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                {{--
                <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                --}}
                <li class="breadcrumb-item active">All Products</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{csrf_field()}}
                <products></products>
            </div>
        </div>
    </div>
@stop
