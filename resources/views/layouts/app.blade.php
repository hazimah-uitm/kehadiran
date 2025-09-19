<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" href="{{ asset('public/assets/images/uitm-favicon.png') }}" type="image/png" />
  <link href="{{ asset('public/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
  <link href="{{ asset('public/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
  <link href="{{ asset('public/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('public/assets/css/pace.min.css') }}" rel="stylesheet" />
  <script src="{{ asset('public/assets/js/pace.min.js') }}"></script>
  <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('public/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link href="{{ asset('public/assets/css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('public/assets/css/icons.css') }}" rel="stylesheet">
  <title>{{ config('app.name', 'Sistem Kehadiran') }}</title>

  <style>
    :root { --nav-h: 64px; }               /* anggaran tinggi navbar */
    @media (min-width: 992px){ :root{ --nav-h: 72px; } }
    body.has-floating { padding-top: calc(var(--nav-h) + 16px); }
    /* supaya list-group item flush tak “langgar” border card */
    .card .list-group-item { border-left: 0; border-right: 0; }
    .custom-navbar { background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .navbar-brand { letter-spacing:.3px; }
    /* floating toast pos bawah navbar */
    .floating-msg { position: fixed; left: 50%; transform: translateX(-50%); top: calc(var(--nav-h) + 12px); z-index: 1080; }
  </style>
</head>
<body class="{{ session('success') || session('error') ? 'has-floating' : '' }}">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg sticky-top custom-navbar">
    <div class="container">
      <a class="navbar-brand text-uppercase" href="{{ route('public.programs') }}">Sistem Kehadiran</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link text-uppercase" href="{{ route('public.programs') }}">Senarai Program</a>
          </li>

          @guest
            <li class="nav-item">
              <a class="nav-link text-uppercase" href="{{ route('login') }}">Log Masuk Admin</a>
            </li>
          @endguest

          @hasanyrole('Superadmin|Admin')
            <li class="nav-item">
              <a class="nav-link text-uppercase" href="{{ route('attendance.create') }}">Kehadiran</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" href="{{ route('home') }}">Dashboard</a>
            </li>
            <li class="nav-item">
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="ms-lg-2">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-sm btn-outline-dark text-uppercase">Log Keluar</button>
              </form>
            </li>
          @endhasanyrole
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->

  <!-- Floating alerts -->
  @if (session('success'))
    <div class="floating-msg">
      <div class="alert alert-success alert-dismissible fade show bg-light bg-opacity-75 shadow-sm" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  @if (session('error'))
    <div class="floating-msg">
      <div class="alert alert-danger alert-dismissible fade show bg-light bg-opacity-75 shadow-sm" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  {{-- PAGE CONTENT --}}
  @yield('content')

  <!-- Scripts -->
  <script src="{{ asset('public/assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('public/assets/js/app.js') }}"></script>
</body>
</html>
