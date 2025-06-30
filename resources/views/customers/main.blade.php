<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="favicon.png">

    <meta name="description" content="" />

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets_customers/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets_customers/css/tiny-slider.css')}}" rel="stylesheet">
    <link href="{{ asset('assets_customers/css/style.css')}}" rel="stylesheet">
    <title>A68 Laundry</title>
</head>

<body>
    @include('customers.components.navbar')
    @include('customers.components.hero')
    @include('customers.components.laundry')
    @include('customers.components.paket')
    @include('customers.components.whyChooseMe')
    @include('customers.components.footer')
    
    <script src="{{asset('assets_customers/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets_customers/js/tiny-slider.js')}}"></script>
    <script src="{{asset('assets_customers/js/custom.js')}}"></script>
</body>

</html>