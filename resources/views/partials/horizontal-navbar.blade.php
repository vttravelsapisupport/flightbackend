<nav class="navbar top-navbar col-lg-12 col-12 p-0">
    <div class="container-fluid  mx-4">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo" href="{{ url('/dashboard') }}">
                <img src="{{ asset('/images/logo.png') }}" alt="logo" style="height: 30px!important;"/>
            </a>
            <a class="navbar-brand brand-logo-mini" href="{{ url('/dashboard') }}">
                <img src="{{ asset('/images/logo.png') }}" alt="logo"/></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

            <ul class="navbar-nav navbar-nav-right">

                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
                        <img src="{{ asset('assets/images/dashboard/avtar2.png') }}" alt="profile">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="{{ route('activity-logs') }}">
                            <i class="mdi mdi-settings"></i>
                            Activity Log
                        </a>
                        <a class="dropdown-item" href="{{ route('change-password') }}">
                            <i class="mdi mdi-settings"></i>
                            Change Password
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}"onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout"></i>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </div>
</nav>
