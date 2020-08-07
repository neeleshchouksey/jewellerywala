@extends('admin.layouts.layout-basic')
<style>
    a:hover{
        color:#ffde00 !important
    }
</style>
@section('content')
    <div class="main-content page-profile">
        <div class="page-header">
            <h3 class="page-title"><i class="icon-fa icon-fa-credit-card-alt"></i> Shipping Charges</h3>

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                {{--
                <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                --}}
                <li class="breadcrumb-item active">Shipping Charges</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{csrf_field()}}
                <shipping-charges></shipping-charges>
            </div>
        </div>
    </div>
@stop
