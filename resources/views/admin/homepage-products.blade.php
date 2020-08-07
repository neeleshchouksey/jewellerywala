@extends('admin.layouts.layout-basic')
<style>
    a:hover{
        color:#ffde00 !important
    }
</style>
@section('content')
    <div class="main-content page-profile">
        <div class="page-header">
            <h3 class="page-title"><i class="icon-fa icon-fa-product-hunt"></i> Manage Products</h3>

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                {{--
                <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                --}}
                <li class="breadcrumb-item active">Add Products to Homepage</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{csrf_field()}}
                <homepage-products></homepage-products>
            </div>
        </div>
    </div>
@stop
