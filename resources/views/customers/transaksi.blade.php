<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A68 Laundry</title>

    <link rel="shortcut icon" href="favicon.png">
    <meta name="description" content="">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets_customers/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_customers/css/style.css') }}" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    @include('customers.components.navbar')

    <!-- SweetAlert Notifications -->
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
            </div>
        </div>
    </div>

    <!-- Main Section -->
    <div class="container-fluid mt-5 mb-5" style="max-width: 90vw;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Order</th>
                        <th>Jenis Laundry</th>
                        <th>Berat</th>
                        <th>Harga</th>
                        <th>Ongkir</th>
                        <th>Total</th>
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
                            <td>{{ $o->orderan->jenisLaundry->nama }}</td>
                            <td>{!! $o->orderan->berat ? $o->orderan->berat . ' kg' : '<span class="badge bg-warning">Menunggu Berat</span>' !!}</td>
                            <td>{!! $o->orderan->harga
                                ? 'Rp ' . number_format($o->orderan->harga, 0, ',', '.')
                                : '<span class="badge bg-warning">Menunggu Harga</span>' !!}</td>
                            <td>{!! $o->ongkir
                                ? 'Rp ' . number_format($o->ongkir, 0, ',', '.')
                                : '<span class="badge bg-warning">Belum Ada Ongkir</span>' !!}</td>
                            <td>
                                @if ($o->orderan->harga && $o->ongkir)
                                    Rp {{ number_format($o->orderan->harga + $o->ongkir, 0, ',', '.') }}
                                @else
                                    <span class="badge bg-warning">Menunggu Harga/Ongkir</span>
                                @endif
                            </td>
                            <td>
                                @if (!$o->orderan->harga)
                                    <span class="badge bg-warning">Menunggu Harga</span>
                                @endif
                                @if ($o->orderan->metode_pembayaran)
                                    <span
                                        class="badge bg-success">{{ strtoupper($o->orderan->metode_pembayaran) }}</span>
                                    @if (
                                        $o->orderan->metode_pembayaran == 'Transfer' &&
                                            !$o->orderan->statusPembayaran->contains('status', 'Pembayaran Berhasil'))
                                        <button class="badge bg-warning border-0"
                                            onclick="bayar({{ $o->orderan->id }})">Klik Untuk Bayar</button>
                                    @endif
                                @endif
                                @if ($o->orderan->harga && !$o->orderan->statusPembayaran->contains('status', 'Pembayaran Berhasil'))
                                    <form id="metodeForm{{ $o->orderan->id }}">@csrf
                                        <select name="metode_pembayaran"
                                            class="form-select form-select-sm d-inline w-auto metode-select"
                                            data-order-id="{{ $o->orderan->id }}">
                                            <option value="" disabled selected>Pilih Metode</option>
                                            <option value="Paket">Paket</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </form>
                                @endif
                                @if ($o->orderan->metode_pembayaran == 'Paket' && !$o->orderan->statusPembayaran->contains('status', 'Pembayaran Berhasil'))
                                    <form id="paketForm{{ $o->orderan->id }}">@csrf
                                        <select name="paket"
                                            class="form-select form-select-sm d-inline w-auto metode-select"
                                            data-order-id="{{ $o->orderan->id }}">
                                            <option value="" disabled selected>Pilih Paket</option>
                                            @foreach ($paketSaya as $ps)
                                                <option value="{{ $ps->id }}">
                                                    {{ $ps->paketLaundry->jenisLaundry->nama . ' - SISA ' . $ps->paketLaundry->berat . 'KG' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @if ($o->jarak)
                                    @if (in_array($o->orderan->statusCucian->last()->status, ['Orderan Masuk', 'Lokasi Jemput Diperbarui']))
                                        <ul>
                                            <li><a href="https://www.google.com/maps/search/?api=1&query={{ $o->latitude }},{{ $o->longitude }}"
                                                    target="_blank">Link Gmaps</a></li>
                                            <li><a href="/setLocation/{{ $o->orderan->id }}">Update Lokasi</a></li>
                                        </ul>
                                    @else
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $o->latitude }},{{ $o->longitude }}"
                                            target="_blank" class="badge bg-success">Lihat di Gmaps</a>
                                    @endif
                                @else
                                    <a href="/setLocation/{{ $o->orderan->id }}" class="btn btn-sm btn-primary">Set
                                        Lokasi</a>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary status-toggle" data-bs-toggle="collapse"
                                    data-bs-target="#statusPembayaran{{ $o->orderan->id }}">Klik untuk melihat
                                    status</span>
                                <div class="collapse mt-2" id="statusPembayaran{{ $o->orderan->id }}">
                                    <ul>
                                        @foreach ($o->orderan->statusPembayaran as $sp)
                                            <li><span
                                                    class="badge bg-info"><b>{{ $sp->status }}</b></span><br>{{ $sp->tgl }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary status-toggle" data-bs-toggle="collapse"
                                    data-bs-target="#statusCucian{{ $o->orderan->id }}">Klik untuk melihat
                                    status</span>
                                <div class="collapse mt-2" id="statusCucian{{ $o->orderan->id }}">
                                    <ul>
                                        @foreach ($o->orderan->statusCucian as $sc)
                                            <li><span
                                                    class="badge bg-info"><b>{{ $sc->status }}</b></span><br>{{ $sc->tgl }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if (in_array($o->orderan->statusCucian->last()->status, ['Orderan Masuk', 'Lokasi Jemput Diperbarui']))
                                            <li><a class="dropdown-item" href="/batalkanOrder/{{ $o->orderan->id }}"
                                                    onclick="return confirm('Batalkan order ini?');"><i
                                                        class="fas fa-times-circle"></i> Batalkan Order</a></li>
                                        @endif
                                        @if ($o->orderan->statusCucian->last()->status == 'Cucian Diantar')
                                            <li><a class="dropdown-item" href="/cucianSelesai/{{ $o->orderan->id }}"
                                                    onclick="return confirm('Selesaikan order ini?');"><i
                                                        class="fas fa-check-circle"></i> Cucian Selesai</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('customers.components.footer')

    <div id="loader"
        class="d-none position-fixed top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex justify-content-center align-items-center"
        style="z-index: 9999;">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets_customers/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets_customers/js/custom.js') }}"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.metode-select').forEach(select => {
                select.addEventListener('change', function() {
                    const orderId = this.dataset.orderId;
                    const form = document.getElementById(this.form.id);
                    const token = form.querySelector('input[name="_token"]').value;
                    const url = this.name === 'metode_pembayaran' ?
                        `/setMetodePembayaran/${orderId}` : `/paymentPaket/${orderId}`;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                [this.name]: this.value
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire({
                                icon: data.success ? 'success' : 'error',
                                title: data.success ? 'Berhasil' : 'Gagal',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                if (data.success) location.reload();
                            });
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Terjadi kesalahan.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        });
                });
            });
        });

        function bayar(id) {
            $('#loader').removeClass('d-none');
            $.ajax({
                url: '/transaksi/payment',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    _token: '{{ csrf_token() }}',
                    id
                },
                success: function(response) {
                    $('#loader').addClass('d-none');
                    snap.pay(response.snap_token, {
                        onSuccess: () => window.location.href = 'transaksi/payment/success/' + response
                            .snap_token,
                        onPending: result => console.log("Pending:", result),
                        onError: () => Swal.fire("Gagal!", "Transaksi gagal atau dibatalkan.", "error")
                    });
                },
                error: function() {
                    $('#loader').addClass('d-none');
                    Swal.fire("Gagal!", "Terjadi kesalahan saat memproses data.", "error");
                }
            });
        }
    </script>
</body>

</html>
