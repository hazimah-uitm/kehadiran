<div class="sidebar-header">
    <div>
        <img src="{{ asset('public/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
    </div>
    <div>
        <h4 class="logo-text">MAIN</h4>
        <h6 class="logo-subtitle">Template System</h6>
    </div>
    <div class="toggle-icon ms-auto" id="toggle-icon"><i class='bx bx-arrow-to-left'></i></div>
</div>

<!--navigation-->
<ul class="metismenu" id="menu">
    <li class="{{ Request::routeIs('home') ? 'mm-active' : '' }}">
        <a href="{{ route('home') }}">
            <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
            <div class="menu-title">Dashboard</div>
        </a>
    </li>

    <li class="{{ Request::routeIs('activity-log') ? 'mm-active' : '' }}">
        <a href="{{ route('activity-log') }}">
            <div class="parent-icon"><i class='bx bx-history'></i></div>
            <div class="menu-title">Log Aktiviti</div>
        </a>
    </li>

    <li class="menu-label">Pengurusan Pengguna</li>

    <li class="{{ Request::is('user*') && !Request::is('user-role*') ? 'mm-active' : '' }}">
        <a href="{{ route('user') }}">
            <div class="parent-icon"><i class='bx bx-user-circle'></i></div>
            <div class="menu-title">Pengguna</div>
        </a>
    </li>

    <li class="{{ Request::is('user-role*') ? 'mm-active' : '' }}">
        <a href="{{ route('user-role') }}">
            <div class="parent-icon"><i class='bx bx-shield'></i></div>
            <div class="menu-title">Peranan Pengguna</div>
        </a>
    </li>



    <li class="menu-label">Tetapan</li>

    <li class="{{ Request::is('campus*') ? 'mm-active' : '' }}">
        <a class="has-arrow" href="#">
            <div class="parent-icon"><i class='bx bx-location-plus'></i></div>
            <div class="menu-title">Lokasi</div>
        </a>
        <ul>
            <li class="{{ Request::is('campus*') ? 'mm-active' : '' }}">
                <a href="{{ route('campus') }}"><i class="bx bx-right-arrow-alt"></i>Kampus</a>
            </li>
        </ul>
    </li>

    <li class="{{ Request::is('position*') ? 'mm-active' : '' }}">
        <a class="has-arrow" href="#">
            <div class="parent-icon"><i class="bx bx-cog"></i></div>
            <div class="menu-title">Tetapan Umum</div>
        </a>
        <ul>
            <li class="{{ Request::is('position*') ? 'mm-active' : '' }}">
                <a href="{{ route('position') }}"><i class="bx bx-right-arrow-alt"></i>Jawatan</a>
            </li>
        </ul>
    </li>

    <li class="{{ Request::routeIs('logs.debug') ? 'mm-active' : '' }}">
        <a href="{{ route('logs.debug') }}">
            <div class="parent-icon"><i class='bx bxs-bug'></i></div>
            <div class="menu-title">Debug Log</div>
        </a>
    </li>
</ul>
<!--end navigation-->