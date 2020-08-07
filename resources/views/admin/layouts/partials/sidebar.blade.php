<div class="sidebar-left @if($type == 'icon') sidebar-large-icons @endif" id="mobile-nav">
    <div class="sidebar-body scroll-pane">
        <?php
        if(Auth::user()->user_type == 0){
           // $sidebar_menu = config('menu.sidebar');
            $sidebar_menu = get_admin_roles();
        }elseif(Auth::user()->user_type == 1){
            $sidebar_menu = config('seller_menu.sidebar');
        }
         ?>
        <ul class="side-nav metismenu" id="menu">
            @foreach($sidebar_menu as $menu)
                <li class="{{set_active($menu['active'],'active')}}">
                    <a href="{{url($menu['link'])}}"><i class="{{$menu['icon']}}"></i> {{$menu['title']}} @if(isset($menu['children'])  && sizeof($menu['children']))<span class="icon-fa arrow icon-fa-fw"></span> @endif</a>
                    @if(isset($menu['children']) && sizeof($menu['children']))
                        <ul aria-expanded="true" class="collapse">
                            @foreach($menu['children'] as $child)
                                <li class="{{set_active($child['active'],'active')}}">
                                    <a href="{{url($child['link'])}}">{{$child['title']}}@if(isset($child['children']))<span class="icon-fa arrow icon-fa-fw"></span> @endif</a>
                                    @if(isset($child['children']))
                                        <ul aria-expanded="true" class="collapse submenu">
                                            @foreach($child['children'] as $subchild)
                                                <li class="{{set_active($subchild['active'])}}"><a href="{{url($subchild['link'])}}">{{$subchild['title']}}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>

