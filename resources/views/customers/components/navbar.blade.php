<!-- Start Header/Navigation -->
<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

    <div class="container">
        <a class="navbar-brand" href="index.html">A68 Laundry<span>.</span></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni"
            aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li><a class="nav-link" href="{{ url('/') }}#laundry">Laundry</a></li>
                <li><a class="nav-link" href="{{ url('/') }}#paket">Paket</a></li>
                <li><a class="nav-link" href="{{ url('/') }}#statusCucian">Status Cucian</a></li>
            </ul>

            <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                @if (Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('assets_customers/images/user.svg') }}">
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/profile">Profile</a></li>
                            <li><a class="dropdown-item" href="/paketSaya">Paket Saya</a></li>
                            <li><a class="dropdown-item" href="/transaksiSaya">Transaksi Saya</a></li>
                            <li><a class="dropdown-item" href="/logout">Logout</a></li>
                        </ul>
                    </li>
                @else
                    <li><a class="nav-link" href="/profile"><img
                                src="{{ asset('assets_customers/images/user.svg') }}"></a></li>
                @endif
                
                <li>
                    <a class="nav-link" href="/keranjang">
                        <img src="{{ asset('assets_customers/images/cart.svg') }}">
                        <span class="badge bg-danger">{{$jumlahKeranjang}}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

</nav>
<!-- End Header/Navigation -->
