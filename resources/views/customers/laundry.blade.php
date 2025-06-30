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
                        <h1>Jenis Laundry</h1>
                    </div>
                </div>
                <div class="col-lg-7">

                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            <div class="row">
                @foreach ($laundry as $l)
                    <div class="col-12 col-md-4 col-lg-3 mb-5">
                        <a class="product-item" href="/keranjang/store/{{$l->id}}">
                            <img src="{{ asset('images/' . $l->foto) }}" class="img-fluid product-thumbnail">
                            <h3 class="product-title">{{$l->nama}}</h3>
                            <strong class="product-price">{{$l->harga}}/KG</strong>
                            <span class="icon-cross">
                                <img src="{{asset('assets_customers/images/cross.svg')}}" class="img-fluid">
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('customers.components.footer')

    <script src="{{ asset('assets_customers/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_customers/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets_customers/js/custom.js') }}"></script>
</body>

</html>
