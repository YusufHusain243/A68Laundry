<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="favicon.png">

    <meta name="description" content="" />

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets_customers/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets_customers/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_customers/css/style.css') }}" rel="stylesheet">
    <title>A68 Laundry</title>
</head>

<body>
    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Login Page</h1>
                        <p class="mb-4">Selamat Datang Pelanggan Setia A68 Laundry, Silahkan Login Untuk Melanjutkan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->


    <!-- Start Contact Form -->
    <div class="untree_co-section">
        <div class="container">
            <div class="block">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-8 pb-4">
                        <form action="/loginCustomer" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="text-black" for="username">Username</label>
                                <input type="username" class="form-control" name="username" id="username">
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="text-black" for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password">
                            </div>
                            <br>
                            Belum Memiliki Akun? <b><a href="/registerCustomer">Register Disini</a></b>
                            <br>
                            <button type="submit" class="btn btn-primary-hover-outline">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('customers.components.footer')
</body>

</html>
