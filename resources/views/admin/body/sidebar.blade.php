<aside class="left-sidebar bg-sidebar">
  <div id="sidebar" class="sidebar sidebar-static sidebar-minified">
    <!-- Aplication Brand -->
    <div class="app-brand">
      <a href="{{ route('dashboard') }}" title="Sleek Dashboard">
        <span class="brand-name text-truncate">Menu</span>
      </a>
    </div>
    <!-- begin sidebar scrollbar -->
    <div class="sidebar-scrollbar">

      <!-- sidebar menu -->
      <ul class="nav sidebar-inner" id="sidebar-menu">
          <li>
            <a class="sidenav-item-link" href="{{ route('dashboard') }}">
              <i class="mdi mdi-home-account"></i>
              <span class="nav-text">Dashboard</span>
            </a>
          </li>
          @canany(['view products','manage products','manage stocks'])
            <li class="has-sub">
              <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#products"
                aria-expanded="false" aria-controls="products">
              <i class="mdi mdi-account-group"></i>
                <span class="nav-text">Products</span><b class="caret"></b>
              </a>
              <ul  class="collapse"  id="products"
                data-parent="#sidebar-menu">
                <div class="sub-menu">
                @canany(['view products','manage products'])
                  <li >
                    <a class="sidenav-item-link" href="{{ route('products.index') }}">
                      <span class="nav-text">Info</span>
                    </a>
                  </li>
                @endcanany
                  @can('manage stocks')
                  <li >
                    <a class="sidenav-item-link" href="{{ route('stocks') }}">
                      <span class="nav-text">Stocks</span>
                    </a>
                  </li>
                  <li >
                    <a class="sidenav-item-link" href="{{ route('procurement.index') }}">
                      <span class="nav-text">Procurement</span>
                    </a>
                  </li>
                  <li >
                    <a class="sidenav-item-link" href="{{ route('expiry') }}">
                      <span class="nav-text">Expiry</span>
                    </a>
                  </li>
                  <li >
                    <a class="sidenav-item-link" href="{{ route('batch.index') }}">
                      <span class="nav-text">Batch</span>
                    </a>
                  </li>
                  @endcan
                </div>
              </ul>
            </li>
          @endcanany
          @can('manage users')
          <li>
            <a class="sidenav-item-link" href="{{ url('/logs') }}">
              <i class="mdi mdi-cash-multiple"></i>
              <span class="nav-text">Activity Logs</span>
            </a>
          </li>
          @endcan

          @can('use POS')
            <li>
              <a class="sidenav-item-link" href="{{ route('cart.index') }}">
              <i class="mdi mdi-cart"></i>
                <span class="nav-text">POS</span>
              </a>
            </li>
          @endcan
          @can('view transactions')
            <li>
              <a class="sidenav-item-link" href="{{ url('/orders') }}">
              <i class="mdi mdi-view-list"></i>
                <span class="nav-text">Orders History</span>
              </a>
            </li>
          @endcan
          <!--@if(Auth::guard('admin')->check())
          <li>
            <a class="sidenav-item-link" href="{{ route('showqr') }}">
            <i class="mdi mdi-qrcode"></i>
              <span class="nav-text">QR Code</span>
            </a>
          </li>
          @endif !-->
          @can('manage users')
          <li class="has-sub">
            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#accounts"
              aria-expanded="false" aria-controls="accounts">
            <i class="mdi mdi-account-group"></i>
              <span class="nav-text">Accounts</span><b class="caret"></b>
            </a>
            <ul  class="collapse"  id="accounts"
              data-parent="#sidebar-menu">
              <div class="sub-menu">
                <li>
                  <a class="sidenav-item-link" href="{{ url('/roles') }}">
                    <span class="nav-text">Assign Permissions</span>
                  </a>
                </li>
                <li >
                  <a class="sidenav-item-link" href="{{ url('/roles/assign') }}">
                    <span class="nav-text">Assign Roles</span>
                  </a>
                </li>
                
              </div>
            </ul>
          </li>
          @endcan
          @role('admin')
          <li class="has-sub" >
            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#settings"
              aria-expanded="false" aria-controls="settings">
              <i class="mdi mdi-account-box-multiple"></i>
              <span class="nav-text">Profile</span> <b class="caret"></b>
            </a>
            <ul  class="collapse"  id="settings"
              data-parent="#sidebar-menu">
              <div class="sub-menu">
              {{--@if(Auth::guard('web')->check())
                <li >
                  <a class="sidenav-item-link" href="{{ route('user.profile') }}">
                    <span class="nav-text">Profile</span>
                  </a>
                </li>

                <li >
                  <a class="sidenav-item-link" href="{{ route('change.password') }}">
                    <span class="nav-text">Change Password</span>
                  </a>
                </li>--}}
              @if(Auth::guard('admin')->check())
              <li >
                  <a class="sidenav-item-link" href="{{ route('admin.profile') }}">
                    <span class="nav-text">Profile</span>
                  </a>
                </li>

                <li >
                  <a class="sidenav-item-link" href="{{ route('adminchange.password') }}">
                    <span class="nav-text">Change Password</span>
                  </a>
                </li>
              @endif
              </div>
            </ul>
          </li>
          @endrole
      </ul>

    </div>

    
  </div>
</aside>
