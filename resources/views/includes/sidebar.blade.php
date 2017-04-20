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
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-shopping-cart"></i><span> 訂單 </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('orders') }}"><span>當月</span></a></li>
                        <li><a href="{{ url('orders', ['all']) }}"><span>全部</span></a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ url('clients') }}" class="waves-effect {{ current_page('clients') ? 'active' : '' }}"><i class="icon-people"></i> <span> 客戶名單 </span> </a>
                </li>

                <li>
                    <a href="{{ url('products') }}" class="waves-effect {{ current_page('products') ? 'active' : '' }}"><i class="glyphicon glyphicon-barcode"></i><span> 產品 </span> </a>
                </li>

                <li>
                    <a href="{{ url('reporting') }}" class="waves-effect {{ current_page('reporting') ? 'active' : '' }}"><i class="glyphicon glyphicon-list-alt"></i><span> 訂單報告 </span> </a>
                </li>

                {{-- <li>
                    <a href="{{ url('settings') }}" class="waves-effect {{ current_page('settings') ? 'active' : '' }}"><i class="ti-settings"></i><span> 設定 </span> </a>
                </li> --}}

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>