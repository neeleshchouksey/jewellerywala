@extends('admin.layouts.layout-basic')
<style>
    a:hover{
        color:#ffde00 !important
    }
</style>
@section('content')
    <div class="main-content page-profile">
        <div class="page-header">
            <h3 class="page-title"><i class="icon-fa icon-fa-google-wallet"></i> Manage Coupon</h3>

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                {{--<li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>--}}
                <li class="breadcrumb-item active">Manage Coupon</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        {{csrf_field()}}
                        <coupon-code></coupon-code>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
