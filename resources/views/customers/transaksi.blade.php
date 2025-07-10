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

    <!-- Notifikasi SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>

    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Transaksi Saya</h1>
                    </div>
                </div>
                <div class="col-lg-7"></div>
            </div>
        </div>
    </div>

    <!-- Main Section -->
    <div class="untree_co-section before-footer-section">
        <div class="container">
            <div class="row mb-5">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode Order</th>
                            <th>Berat Laundry</th>
                            <th>Harga Laundry</th>
                            <th>Metode Pembayaran</th>
                            <th>Lokasi</th>
                            <th>Status Pembayaran</th>
                            <th>Status Cucian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderan as $o)
                            <tr>
                                <td>{{ $o->orderan->kode_order }}</td>
                                <td>
                                    {!! $o->orderan->berat ? $o->orderan->berat . ' kg' : '<span class="badge bg-warning">Menunggu Berat</span>' !!}
                                </td>
                                <td>
                                    {!! $o->orderan->harga ? 'Rp' . $o->orderan->harga : '<span class="badge bg-warning">Menunggu Harga</span>' !!}
                                </td>
                                <td>
                                    @if ($o->orderan->metode_pembayaran)
                                        {{ $o->orderan->metode_pembayaran }}
                                    @else
                                        @if ($o->orderan->harga)
                                            <form action="" method="POST">
                                                @csrf
                                                <select name="metode_pembayaran"
                                                    class="form-select form-select-sm d-inline w-auto" required>
                                                    <option value="" disabled selected>Pilih Metode</option>
                                                    <option value="paket">Paket</option>
                                                    <option value="Transfer">Transfer</option>
                                                </select>
                                            </form>
                                        @else
                                            <span class="badge bg-warning">Menunggu Harga</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($o->jarak)
                                        <ul>
                                            <li><a href="https://www.google.com/maps/search/?api=1&query={{ $o->latitude }},{{ $o->longitude }}"
                                                    target="_blank">Link Gmaps</a></li>
                                            <li><a href="/setLocation/{{ $o->orderan->id }}">Update Lokasi</a></li>
                                        </ul>
                                    @else
                                        <a href="/setLocation/{{ $o->orderan->id }}" class="btn btn-sm btn-primary">Set
                                            Lokasi</a>
                                    @endif
                                </td>
                                <td>
                                    <ul>
                                        @foreach ($o->orderan->statusPembayaran as $sp)
                                            <li>
                                                <b>{{ $sp->status }}</b><br>
                                                {{ $sp->tgl }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        @foreach ($o->orderan->statusCucian as $sc)
                                            <li>
                                                <b>{{ $sc->status }}</b><br>
                                                {{ $sc->tgl }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="aksiDropdown{{ $o->id }}" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="aksiDropdown{{ $o->id }}">
                                            <li>
                                                <a class="dropdown-item" href="">
                                                    <i class="fas fa-eye"></i> Set Lokasi
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('customers.components.footer')

    <!-- Scripts -->
    <script src="{{ asset('assets_customers/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_customers/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets_customers/js/custom.js') }}"></script>
</body>

</html>
