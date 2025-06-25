<!doctype html>
<html lang="en" dir="ltr" data-bs-theme="auto">

<head>
    <script src="{{ asset('assets_customer/js/color-modes.js') }}"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="tigmatemplate">
    <meta name="generator" content="Bootstrap">
    <title>E-Laundry - A68 </title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets_customer/logo/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets_customer/logo/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets_customer/logo/favicon-16x16.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets_customer/logo/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('assets_customer/logo/site.webmanifest') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets_customer/libraries/glide/css/glide.core.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_customer/libraries/aos/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_customer/css/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_customer/css/style.css') }}">
</head>

<body>
    <div class="overflow-hidden py-5 py-sm-6 py-xl-7">
        <div class="container">
            <div class="row gy-5 gx-sm-5">
                <div class="col-12 col-xl-5 pt-4">
                    <div class="mx-auto max-w-2xl">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <h2 class="m-0 text-primary-emphasis text-base leading-7 fw-semibold">
                            Biodata
                        </h2>
                        <p class="m-0 mt-2 text-body-emphasis text-3xl tracking-tight fw-bold">
                            Hai Pelanggan Setia A68 Laundry
                        </p>
                    </div>

                    <div class="mx-auto max-w-2xl mt-2">
                        <form class="row g-4 needs-validation" novalidate action="/biodata/update" method="POST">
                            @csrf
                            <div>
                                <label for="nama" class="form-label">
                                    Nama
                                    <span class="text-danger-emphasis">*</span>
                                </label>
                                <input type="text" class="form-control" name="nama" value="{{$user->nama}}" id="nama" required>
                                <div class="invalid-feedback text-xs">
                                    Please enter your name.
                                </div>
                            </div>
                            <div>
                                <label for="no_hp" class="form-label">
                                    No. WA
                                    <span class="text-danger-emphasis">*</span>
                                </label>
                                <input type="text" class="form-control" name="no_hp" value="{{$user->no_hp}}" id="no_hp" required>
                                <div class="invalid-feedback text-xs">
                                    Please enter your phone number.
                                </div>
                            </div>
                            <div>
                                <label for="email" class="form-label">
                                    Email
                                    <span class="text-danger-emphasis">*</span>
                                </label>
                                <input type="email" class="form-control" name="email" value="{{$user->email}}" id="email" required>
                                <div class="invalid-feedback text-xs">
                                    Please enter your email.
                                </div>
                            </div>
                            <div>
                                <label for="alamat" class="form-label">
                                    Alamat
                                    <span class="text-danger-emphasis">*</span>
                                </label>
                                <input type="text" class="form-control" name="alamat" value="{{$user->alamat}}" id="alamat" required>
                                <div class="invalid-feedback text-xs">
                                    Please enter your address.
                                </div>
                            </div>
                            <div>
                                <label for="username" class="form-label">
                                    Username
                                    <span class="text-danger-emphasis">*</span>
                                </label>
                                <input type="text" class="form-control" name="username" value="{{$user->username}}" id="username" required>
                                <div class="invalid-feedback text-xs">
                                    Please enter your username.
                                </div>
                            </div>
                            <div>
                                <label class="form-label">
                                    Reset Password?
                                </label>
                                <button type="button" class="btn btn-link" id="resetPasswordButton">Click here to reset</button>
                            </div>
                            <div id="passwordField" style="display: none;">
                                <label for="password" class="form-label">
                                    New Password
                                    <span class="text-danger-emphasis">*</span>
                                </label>
                                <input type="password" class="form-control" name="password" id="password" required>
                                <div class="invalid-feedback text-xs">
                                    Please enter your password.
                                </div>
                                <button type="button" class="btn btn-link" id="cancelResetButton">Cancel</button>
                            </div>
                            <script>
                                document.getElementById('resetPasswordButton').addEventListener('click', function() {
                                    document.getElementById('passwordField').style.display = 'block';
                                });
                                document.getElementById('cancelResetButton').addEventListener('click', function() {
                                    document.getElementById('passwordField').style.display = 'none';
                                });
                            </script>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-lg btn-primary text-white text-sm fw-semibold mt-3"
                                    id="loginButton">
                                    Update Biodata
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="d-none d-xl-block col-12 col-xl-7" data-aos-delay="0" data-aos="fade"
                    data-aos-duration="3000">
                    <div class="h-100 position-relative ms-xxl-5">
                        <div class="position-absolute top-0 end-0 bottom-0 start-0 z-n1 rounded-5">
                            <img src="{{ asset('assets_customer/img/bg/bg7.jpg') }}"
                                class="w-100 h-100 rounded-3 object-fit-cover" loading="lazy" alt="Image description">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript: Bundle with Popper -->
    <script src="{{ asset('assets_customer/libraries/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_customer/libraries/glide/glide.min.js') }}"></script>
    <script src="{{ asset('assets_customer/libraries/aos/aos.js') }}"></script>
    <script src="{{ asset('assets_customer/js/scripts.js') }}"></script>
    <script src="{{ asset('assets_customer/php/contact/script.js') }}"></script>
</body>

</html>
