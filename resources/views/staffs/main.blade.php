<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title') | E-Laundry A68</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets_admin/img/logo.png') }}" type="image/x-icon">

    <!-- Icon Fonts -->
    <link rel="stylesheet" href="{{ asset('assets_admin/fonts/phosphor/duotone/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_admin/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_admin/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_admin/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_admin/fonts/material.css') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets_admin/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets_admin/css/style-preset.css') }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @stack('styles')
</head>

<body>
    <!-- Loader -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    @include('staffs.components.sidebar_staf')
    @include('staffs.components.header_staf')

    <!-- Main Content -->
    <div class="pc-container">
        @yield('content')
    </div>

    @include('staffs.components.footer')

    <!-- Scripts -->
    <script src="{{ asset('assets_admin/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets_admin/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets_admin/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets_admin/js/script.js') }}"></script>
    <script src="{{ asset('assets_admin/js/plugins/feather.min.js') }}"></script>

    <!-- Load jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY')}}"></script>
    @stack('scripts')
</body>

</html>
