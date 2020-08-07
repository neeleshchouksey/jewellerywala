@extends('admin.layouts.layout-login-2')

@section('scripts')
    <script src="/public/assets/admin/js/sessions/login.js"></script>
@stop

@section('content')
    <form action="{{route('send-reset-link')}}" id="loginForm" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <input type="email" class="form-control form-control-danger" placeholder="Enter email" name="email">
        </div>
        <button class="btn btn-theme btn-full"><b>Send Reset Link</b></button>
    </form>
@stop
