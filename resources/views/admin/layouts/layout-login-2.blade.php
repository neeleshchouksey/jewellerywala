<!DOCTYPE html>
<html>
<head>
    <title>Jewellerywala.com</title>
    <link href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'
          type='text/css'>
    <link href="{{ asset('/public/assets/admin/css/laraspace.css') }}" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        var APP_URL = "{{URL::to('/')}}";
        var AUTH_USER = "{{Auth::user()}}";
    </script>
    @include('admin.layouts.partials.favicons')
</head>
<body class="login-page login-2">
<div id="app" class="login-wrapper">
    <div class="login-box">

        @if (Session::has('flash_notification.message'))
            <div class="laraspace-notify hidden-xs-up" data-driver="{{config('laraspace.notification')}}" data-notify="{{ Session::get('flash_notification.level') }}" data-message="{{ Session::get('flash_notification.message') }}">
            </div>
        @endif

        @if($errors->any())
            <div class="laraspace-notify hidden-xs-up" data-driver="{{config('laraspace.notification')}}" data-notify="error" data-message="{{ $errors->first() }}">
            </div>
        @endif

        <div class="logo-main">
            <a href="/"><img src="{{URL::to("/")}}/public/assets/admin/img/logo-login.png" alt="Logo"></a>
        </div>
        @yield('content')
        <div class="page-copyright">
{{--            <p>Powered by <a href="http://bytefury.com" target="_blank">Bytefury</a></p>--}}
{{--            <p>Laraspace © {{ date('Y') }}</p>--}}
        </div>
    </div>
</div>
<script src="{{asset('/public/assets/admin/js/core/plugins.js')}}"></script>
<script src="{{asset('/public/assets/admin/js/core/app.js')}}"></script>
@yield('scripts')
</body>
</html>
