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
    @include('customers.components.navbar')
    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Profile Page</h1> <!-- # BEGIN: Updated Title -->
                        <p class="mb-4">Selamat Datang! Pelanggan Setia A68 Laundry</p>
                        <!-- # BEGIN: Updated Description -->
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
                        <form action="/profile/update" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="text-black" for="nama">Nama</label>
                                <input type="text" class="form-control" name="nama" value="{{ $profile->nama }}"
                                    id="nama" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="text-black" for="no_hp">No HP</label>
                                <input type="text" class="form-control" name="no_hp" value="{{ $profile->no_hp }}"
                                    id="no_hp" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="text-black" for="email">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $profile->email }}"
                                    id="email" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="text-black" for="alamat">Alamat</label>
                                <textarea class="form-control" name="alamat" id="alamat" required>{{ $profile->alamat }}</textarea>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="text-black" for="username">Username</label>
                                <input type="text" class="form-control" name="username"
                                    value="{{ $profile->username }}" id="username" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="text-black" for="reset-password" style="cursor: pointer;"
                                    onclick="togglePasswordField()">Klik Disini Untuk Reset Password</label>
                                <input type="password" class="form-control" name="password" id="password" required style="display: none;">
                            </div>
                            <script>
                                function togglePasswordField() {
                                    var passwordField = document.getElementById('password');
                                    if (passwordField.style.display === 'none') {
                                        passwordField.style.display = 'block';
                                        passwordField.required = true;
                                    } else {
                                        passwordField.style.display = 'none';
                                        passwordField.required = false;
                                    }
                                }
                            </script>
                            <br>
                            <button type="submit" class="btn btn-primary-hover-outline">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('customers.components.footer')
    <script src="{{asset('assets_customers/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets_customers/js/tiny-slider.js')}}"></script>
    <script src="{{asset('assets_customers/js/custom.js')}}"></script>
</body>

</html>
