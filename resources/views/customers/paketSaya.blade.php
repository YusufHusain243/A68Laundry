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
                        <h1>Paket Saya</h1>
                    </div>
                </div>
                <div class="col-lg-7"></div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section before-footer-section">
        <div class="container">
            <div class="row mb-5">
                <div class="site-blocks-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="product-thumbnail">No.</th>
                                <th class="product-name">Paket</th>
                                <th class="product-price">Total KG</th>
                                <th class="product-remove">Sisa KG</th>
                                <th class="product-remove">Terpakai KG</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paketMember as $pm)
                            <tr>
                                <td class="product-thumbnail">{{ $loop->iteration }}</td>
                                <td class="product-name">{{ $pm->paketLaundry->jenisLaundry->nama }}</td>
                                <td class="product-price">{{ $pm->paketLaundry->berat }} KG</td>
                                <td class="product-remove">{{ $pm->kg_sisa }} KG</td>
                                <td class="product-remove">{{ $pm->kg_terpakai }} KG</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('customers.components.footer')

    <script src="{{ asset('assets_customers/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_customers/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets_customers/js/custom.js') }}"></script>
</body>

</html>
