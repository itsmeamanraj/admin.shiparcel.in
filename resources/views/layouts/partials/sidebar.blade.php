<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="" class="sidebar-logo">
            <img src="{{asset('assets/media/logos/shiparcel_logo.png')}}" alt="site logo" class="light-logo">
            <img src="{{asset('assets/media/logos/shiparcel_logo.png')}}" alt="site logo" class="dark-logo">
            <img src="{{asset('assets/media/logos/shiparcel_logo.png')}}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="">
                <a href="{{route('admin.dashboard')}}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title">Application</li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Application</span>
                </a>

                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{route('users.index')}}"><i class="fa fa-users"></i>Users List</a>
                    </li>
                    <li class="mt-4">
                        <a href="{{route('wallets.index')}}"><i class="fa fa-wallet"></i>Wallet Requests</a>
                    </li>

                </ul>
                <!-- <ul class="sidebar-submenu">
                    <li>
                        <a href="{{route('users.index')}}"><i class="fa fa-wallet"></i>Wallet Requests</a>
                    </li>

                </ul> -->
            </li>

            <!-- <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="icon-park-outline:setting-two" class="menu-icon"></iconify-icon>
                    <span>Settings</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{route('user-charges')}}"><i class="fa fa-user"></i> User Charges</a>
                    </li>
                </ul>
            </li> -->
        </ul>
    </div>
</aside>