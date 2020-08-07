@extends('admin.layouts.layout-basic')
<style>
    a:hover{
        color:#ffde00 !important
    }
</style>
@section('content')
    <div class="main-content page-profile">

        <div class="row">
            <div class="col-sm-12">
                {{csrf_field()}}
                <h3>You don't have right to access this page</h3>

            </div>
        </div>
    </div>
@stop
