<!DOCTYPE html>
<html>
<head>
    <title>Jewellerywala.com</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
    <link href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'
          type='text/css'>
    <link rel=stylesheet href='../node_modules/nouislider/distribute/nouislider.min.css' media=all />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@voerro/vue-tagsinput@2.0.2/dist/style.css">
    <script src="{{asset('/public/assets/admin/js/core/pace.js')}}"></script>
    <link href="{{ asset('/public/assets/admin/css/laraspace.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/public/assets/admin/css/custom.css') }}" rel="stylesheet" type="text/css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        var APP_URL = "{{URL::to('/')}}";
        var AUTH_USER = "{{Auth::user()}}";
                @if(Auth::user()->user_type==0)
        var USER_TYPE = "admin";
        var AUTH_USER_ID = "{{Auth::user()->id}}";
                @else
        var USER_TYPE = "seller";
        var AUTH_USER_ID = "{{Auth::user()->id}}";
        @endif
    </script>
    @include('admin.layouts.partials.favicons')
    @yield('styles')
</head>
<body class="layout-default skin-default">
@if (Session::has('flash_notification.message'))
    <div class="laraspace-notify hidden-xs-up" data-driver="{{config('laraspace.notification')}}"
         data-notify="{{ Session::get('flash_notification.level') }}"
         data-message="{{ Session::get('flash_notification.message') }}">
    </div>
@endif

@if($errors->any())
    <div class="laraspace-notify hidden-xs-up" data-driver="{{config('laraspace.notification')}}" data-notify="error"
         data-message="{{ $errors->first() }}">
    </div>
@endif
<div id="app" class="site-wrapper">
    <div class="loading">
        <div class="text-center middle">
            <div class="lds-ellipsis">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    @include('admin.layouts.partials.header')
    <div class="mobile-menu-overlay"></div>
    @include('admin.layouts.partials.sidebar',['type' => 'default'])

    @yield('content')

    @include('admin.layouts.partials.footer')

</div>

<script src="{{asset('/public/assets/admin/js/core/plugins.js')}}"></script>
<script src="{{asset('/public/assets/admin/js/demo/skintools.js')}}"></script>
<script src="../node_modules/nouislider/distribute/nouislider.min.js"></script>
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>

<script src="{{asset('/public/assets/admin/js/core/app.js')}}"></script>

@yield('scripts')

<script>
    $(window).on('load', function () {
        /* ----------------------------------------------------------------
            [ Preloader ]
-----------------------------------------------------------------*/
        $('.loading').fadeOut(500);
    });
</script>

</body>
</html>
