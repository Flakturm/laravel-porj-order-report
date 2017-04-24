<?php 
  function current_page($uri = "/") { 
    return strstr(request()->path(), $uri); 
  } 
?>
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>

                {{-- <li>
                    <a href="{{ url('orders') }}" class="waves-effect {{ current_page('orders') ? 'active' : '' }}"><i class="ti-shopping-cart"></i> <span> 訂單 </span> </a>
                </li> --}}

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-shopping-cart"></i><span> Orders </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('orders') }}"><span>Current month</span></a></li>
                        <li><a href="{{ url('orders', ['all']) }}"><span>All</span></a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ url('clients') }}" class="waves-effect {{ current_page('clients') ? 'active' : '' }}"><i class="icon-people"></i> <span> Clients </span> </a>
                </li>

                <li>
                    <a href="{{ url('products') }}" class="waves-effect {{ current_page('products') ? 'active' : '' }}"><i class="glyphicon glyphicon-barcode"></i><span> Products </span> </a>
                </li>

                <li>
                    <a href="{{ url('reporting') }}" class="waves-effect {{ current_page('reporting') ? 'active' : '' }}"><i class="glyphicon glyphicon-list-alt"></i><span> Reporting </span> </a>
                </li>

                {{-- <li>
                    <a href="{{ url('settings') }}" class="waves-effect {{ current_page('settings') ? 'active' : '' }}"><i class="ti-settings"></i><span> Settings </span> </a>
                </li> --}}

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>