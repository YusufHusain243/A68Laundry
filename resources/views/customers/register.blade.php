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
                        <h1>Register Page</h1> <!-- # BEGIN: Updated Title -->
                        <p class="mb-4">Selamat Datang! Silahkan Daftar untuk Membuat Akun A68 Laundry.</p> <!-- # BEGIN: Updated Description -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->


    <!-- Start Registration Form -->
    <div class="untree_co-section">
        <div class="container">
            <div class="block">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-8 pb-4">
                        <form action="/registerCustomer" method="POST"> <!-- # BEGIN: Updated Action -->
                            @csrf
                            <div class="form-group">
                                <label class="text-black" for="username">Username</label>
                                <input type="text" class="form-control" name="username" id="username" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="text-black" for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="text-black" for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="text-black" for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                            </div>
                            <br>
                            Sudah Memiliki Akun? <b><a href="/loginCustomer">Login Disini</a></b>
                            <br>
                            <button type="submit" class="btn btn-primary-hover-outline">Register</button> <!-- # BEGIN: Updated Button Text -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('customers.components.footer')
</body>

</html>
