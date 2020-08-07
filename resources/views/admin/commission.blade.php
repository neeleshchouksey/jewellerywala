@extends('admin.layouts.layout-basic')

@section('content')
    <div class="main-content page-profile">
        <div class="page-header">
            <h3 class="page-title">Manage Commission</h3>

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                {{--<li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>--}}
                <li class="breadcrumb-item active">Manage Commission</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        {{csrf_field()}}
                        <commission></commission>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
