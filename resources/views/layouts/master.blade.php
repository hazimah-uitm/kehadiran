<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="semi-dark">

<head>
    @include('includes.head')
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">

        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            @include('includes.sidebar')
        </div>
        <!--end sidebar wrapper -->

        <!--start header -->
        <header>
            @include('includes.header')
        </header>
        <!--end header -->

        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--wrapper-->
                @if (session('success'))
                    <div id="floating-success-message" class="position-fixed top-0 start-50 translate-middle-x p-3"
                        style="z-index: 11; display: none; animation: fadeInUp 0.5s ease-out;">
                        <div class="alert alert-success alert-dismissible fade show bg-light bg-opacity-75"
                            role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
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
            </div>
        </div>
        <!--end page wrapper -->

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <footer class="page-footer">
            @include('includes.footer')
        </footer>
    </div>
    <!--end wrapper-->

    <!-- Bootstrap JS -->
    <script src="{{ asset('public/assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>

    <script src="{{ asset('public/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/index.js') }}"></script>
    <!--app JS-->
    <script src="{{ asset('public/assets/js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the toggle icon element
            var toggleIcon = document.getElementById('toggle-icon');

            // Add a click event listener to the toggle icon
            toggleIcon.addEventListener('click', function() {
                // Toggle the class for the arrow icon
                var iconElement = toggleIcon.querySelector('i');
                iconElement.classList.toggle('bx-arrow-to-left');
                iconElement.classList.toggle('bx-arrow-to-right');
            });
        });
    </script>
    <script>
        // Get the current year
        var currentYear = new Date().getFullYear();

        // Update the content of the element with the current year
        document.getElementById("copyright").innerHTML = 'Copyright © ' + currentYear +
            ' <a href="https://sarawak.uitm.edu.my/" target="_blank">UiTM Cawangan Sarawak</a>.';
    </script>
    <script>
        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };
    </script>
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>
        function selectAllGroupCheckboxes(selectAllCheckbox) {
            const group = selectAllCheckbox.closest('.col-md-6');
            const checkboxes = group.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            // Uncheck the Deselect All checkbox in the same group
            const deselectAll = group.querySelector('.deselect-all');
            if (deselectAll) {
                deselectAll.checked = false;
            }
        }

        function deselectAllGroupCheckboxes(deselectAllCheckbox) {
            const group = deselectAllCheckbox.closest('.col-md-6');
            const checkboxes = group.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            // Uncheck the Select All checkbox in the same group
            const selectAll = group.querySelector('.select-all');
            if (selectAll) {
                selectAll.checked = false;
            }
        }
    </script>
</body>

</html>
