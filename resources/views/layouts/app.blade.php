<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('public/assets/images/uitm-favicon.png') }}" type="image/png" />
    <!--plugins-->
    <link href="{{ asset('public/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ asset('public/assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('public/assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('public/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/css/icons.css') }}" rel="stylesheet">
    <title>{{ config('app.name') }}</title>
</head>

<body class="bg-login">
    <!--wrapper-->
    @if (session('success'))
    <div id="floating-success-message" class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 11; display: none; animation: fadeInUp 0.5s ease-out;">
        <div class="alert alert-success alert-dismissible fade show bg-light bg-opacity-75" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>

    <!-- JavaScript to show the message after the page is loaded -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var floatingMessage = document.getElementById('floating-success-message');
            floatingMessage.style.display = 'block';
            setTimeout(function() {
                floatingMessage.style.display = 'none';
            }, 4500); // Adjust the timeout (in milliseconds) based on how long you want the message to be visible
        });
    </script>
    @endif
    @yield('content')
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="{{ asset('public/assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });

        $("#show_hide_password_confirm a").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password_confirm input').attr("type") == "text") {
                $('#show_hide_password_confirm input').attr('type', 'password');
                $('#show_hide_password_confirm i').addClass("bx-hide");
                $('#show_hide_password_confirm i').removeClass("bx-show");
            } else if ($('#show_hide_password_confirm input').attr("type") == "password") {
                $('#show_hide_password_confirm input').attr('type', 'text');
                $('#show_hide_password_confirm i').removeClass("bx-hide");
                $('#show_hide_password_confirm i').addClass("bx-show");
            }
        });
    </script>
    <!--app JS-->
    <script src="{{ asset('public/assets/js/app.js') }}"></script>
</body>

</html>