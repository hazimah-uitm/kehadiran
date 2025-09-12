<!DOCTYPE html>
<html lang="en" class="semi-dark">

<head>
    @include('includes.head')
</head>

<body>
    <!-- wrapper -->
    <div class="wrapper">
        <div class="error-404 d-flex align-items-center justify-content-center vh-100">
            <div class="container">
                <div class="card py-5">
                    <div class="row g-0 align-items-center justify-content-center">
                        <div class="col-md-6 col-xl-5">
                            <div class="card-body text-center text-md-start p-4">
                                <h1 class="display-1">
                                    <span class="text-primary">4</span>
                                    <span class="text-danger">0</span>
                                    <span class="text-success">4</span>
                                </h1>
                                <h2 class="font-weight-bold display-6">Halaman Tidak Ditemui</h2>
                                <p>
                                    Halaman yang anda minta tidak ditemui.
                                    <br>Sila kembali ke halaman sebelumnya.
                                </p>
                                <div class="mt-5">
                                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-md-5 radius-30">Laman Utama</a>
                                    <a href="javascript:history.back();" class="btn btn-outline-dark btn-lg ms-3 px-md-5 radius-30">Kembali</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-5">
                            <img src="/public/assets/images/errors-images/404-error.png" class="img-fluid mx-auto d-block" alt="404 Error">
                        </div>
                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>
    </div>
    <!-- end wrapper -->
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
