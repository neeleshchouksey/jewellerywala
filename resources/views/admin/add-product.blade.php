@extends('admin.layouts.layout-basic')
<style>
    a:hover{
        color:#ffde00 !important
    }
</style>
@section('content')
<div class="main-content page-profile">
    <div class="page-header">
        <h3 class="page-title">Product</h3>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
            {{--
            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
            --}}
            <li class="breadcrumb-item active">Add Product</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    {{csrf_field()}}
                    <add-product></add-product>
                </div>
            </div>
        </div>
    </div>
</div>
    @stop
