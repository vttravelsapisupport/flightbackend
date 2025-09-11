<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center">
        <a class="navbar-brand brand-logo" href="{{ url('/dashboard') }}"><img src="{{ asset('/images/logo.png') }}"
                                                                        alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="{{ url('/dashboard') }}"><img
                src="{{ asset('/images/logo.png') }}" alt="logo"/></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item" style="width: auto; margin-top: 5px;">
                <h6>
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                </h6>
            </li>
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
                    <img src="https://via.placeholder.com/30x30" alt="profile"/>
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
                    <a class="dropdown-item" href="{{ route('change-password') }}">
                        <i class="mdi mdi-settings"></i>
                       Change Api Password
                    </a>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
            <!-- <li class="nav-item nav-settings d-none d-lg-flex">
                <a class="nav-link" href="#" >
                    <i class="mdi mdi-dots-horizontal"></i>
                </a>
            </li> -->
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
