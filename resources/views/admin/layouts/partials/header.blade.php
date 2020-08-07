<header class="site-header">
  <a href="#" class="brand-main">
    <img height="100%" width="220px" src="{{asset('/public/assets/admin/img/logo-desk.png')}}" id="logo-desk" alt="Laraspace Logo" class="d-none d-md-inline ">
    <img src="{{asset('/public/assets/admin/img/logo-mobile.png')}}" id="logo-mobile" alt="Laraspace Logo" class="d-md-none">
  </a>
  <a href="#" class="nav-toggle">
    <div class="hamburger hamburger--htla">
      <span>toggle menu</span>
    </div>
  </a>
    <ul class="action-list">
{{--      <li>--}}
{{--        <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-fa icon-fa-plus"></i></a>--}}
{{--        <div class="dropdown-menu dropdown-menu-right">--}}
{{--          <a class="dropdown-item" href="#"><i class="icon-fa icon-fa-edit"></i> New Post</a>--}}
{{--          <a class="dropdown-item" href="#"><i class="icon-fa icon-fa-tag"></i> New Category</a>--}}
{{--          <div class="dropdown-divider"></div>--}}
{{--          <a class="dropdown-item" href="#"><i class="icon-fa icon-fa-star"></i> Separated link</a>--}}
{{--        </div>--}}
{{--      </li>--}}
      <li>
        <notification></notification>
      </li>
      <li>
        <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="avatar"><img src="{{asset('/public/assets/admin/img/avatars/avatar.png')}}" alt="Avatar"></a>
        <div class="dropdown-menu dropdown-menu-right notification-dropdown">
{{--          <a class="dropdown-item" href="/admin/settings/social"><i class="icon-fa icon-fa-cogs"></i> Settings</a>--}}
          <a class="dropdown-item" href="{{URL::to('/')}}/logout"><i class="icon-fa icon-fa-sign-out"></i> Logout</a>
        </div>
      </li>
    </ul>
</header>
