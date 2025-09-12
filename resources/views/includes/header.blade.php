<div class="topbar d-flex align-items-center">
    <nav class="navbar navbar-expand">
        <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
        <div class="top-menu ms-auto">
            <ul class="navbar-nav align-items-center"></ul>
        </div>
        <div class="user-box dropdown">
            <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-info d-flex align-items-center ps-3">
                    <!-- Profile Image with border and increased size -->
                    <img src="{{ !empty(Auth::user()->profile_image) ? asset('public/storage/' . Auth::user()->profile_image) : asset('public/assets/images/avatars/user.png') }}"
                        alt="Profile Image" class="rounded-circle border border-2 border-primary me-2" width="40"
                        height="40">
                    <!-- Staff ID with improved styling -->
                    <p class="user-name mb-0 text-dark fs-6">{{ Auth::user()->staff_id }}<i class='bx bxs-chevron-down'
                            style="margin-left: 5px;"></i></p>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="dropdown-item" style="pointer-events: none;">
                    <div style="display: flex; align-items: center;">
                        <i class='bx bx-shield'></i><span style="margin-left: 5px;">{{ Auth::user()->name }}</span>
                    </div>
                    <ul style="list-style-type: disc; padding-left: 20px; margin: 0;">
                        @foreach (Auth::user()->getRoleNames() as $role)
                            <li>{{ $role }}</li>
                        @endforeach
                    </ul>
                </li>
                <hr style="margin-top: 5px; margin-bottom: 5px; border-color: #ccc;">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.show', ['id' => Auth::id()]) }}">
                        <i class='bx bx-user'></i> <span>Profil</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('profile.change-password', ['id' => Auth::id()]) }}">
                        <i class='bx bx-lock'></i> <span>Tukar Kata Laluan</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class='bx bx-log-out-circle'></i><span>Log Keluar</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </div>
    </nav>
</div>
