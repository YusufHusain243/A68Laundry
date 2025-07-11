<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="favicon.png">
    <meta name="description" content="" />

    <!-- Bootstrap CSS -->
    {{-- <link href="{{ asset('assets_customers/css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
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
    {{-- <div class="untree_co-section before-footer-section"> --}}
    <div class="container-fluid mt-5 mb-5" style="max-width: 90vw;">
        <div class="row">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode Order</th>
                            <th>Jenis Laundry</th>
                            <th>Berat Laundry</th>
                            <th>Harga Laundry</th>
                            <th>Harga Ongkir</th>
                            <th>Total Harga</th>
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
                                <td>
                                    {!! $o->orderan->berat ? $o->orderan->berat . ' kg' : '<span class="badge bg-warning">Menunggu Berat</span>' !!}
                                </td>
                                <td>
                                    {!! $o->orderan->harga
                                        ? 'Rp ' . number_format($o->orderan->harga, 0, ',', '.')
                                        : '<span class="badge bg-warning">Menunggu Harga</span>' !!}
                                </td>
                                <td>
                                    {!! $o->ongkir
                                        ? 'Rp ' . number_format($o->ongkir, 0, ',', '.')
                                        : '<span class="badge bg-warning">Belum Ada Ongkir</span>' !!}
                                </td>
                                <td>
                                    @if ($o->orderan->harga && $o->ongkir)
                                        Rp {{ number_format($o->orderan->harga + $o->ongkir, 0, ',', '.') }}
                                    @else
                                        <span class="badge bg-warning">Menunggu Harga/Ongkir</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($o->orderan->metode_pembayaran)
                                        @if (
                                            $o->orderan->metode_pembayaran == 'Transfer' &&
                                                $o->orderan->statusPembayaran->last()->status == 'Menunggu Pembayaran')
                                            <span class="badge bg-success">Transfer</span>
                                            <button type="button" class="badge bg-warning border-0"
                                                style="cursor:pointer;" onclick="bayar({{ $o->orderan->id }})">
                                                Klik Untuk Bayar
                                            </button>
                                        @endif

                                        @if (
                                            $o->orderan->metode_pembayaran == 'Transfer' &&
                                                $o->orderan->statusPembayaran->last()->status == 'Pembayaran Berhasil')
                                            <span class="badge bg-success">Transfer</span>
                                        @endif

                                        @if ($o->orderan->metode_pembayaran == 'Paket' && $o->orderan->statusPembayaran->last()->status == 'Menunggu Pembayaran')
                                            <span class="badge bg-success">Paket</span>
                                            <form id="paketForm{{ $o->orderan->id }}">
                                                @csrf
                                                <select name="paket"
                                                    class="form-select form-select-sm d-inline w-auto metode-select"
                                                    data-order-id="{{ $o->orderan->id }}" required>
                                                    <option value="" disabled selected>Pilih Paket</option>
                                                    @foreach ($paketSaya as $ps)
                                                        <option value="{{ $ps->id }}">{{ $ps->paketLaundry->jenisLaundry->nama .' - SISA '. $ps->paketLaundry->berat.'KG' }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @endif
                                    @else
                                        @if ($o->orderan->harga)
                                            <form id="metodeForm{{ $o->orderan->id }}">
                                                @csrf
                                                <select name="metode_pembayaran"
                                                    class="form-select form-select-sm d-inline w-auto metode-select"
                                                    data-order-id="{{ $o->orderan->id }}" required>
                                                    <option value="" disabled selected>Pilih Metode</option>
                                                    <option value="Paket">Paket</option>
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
                                        @if (
                                            $o->orderan->statusCucian->last()->status == 'Orderan Masuk' ||
                                                $o->orderan->statusCucian->last()->status == 'Lokasi Jemput Diperbarui')
                                            <ul>
                                                <li><a href="https://www.google.com/maps/search/?api=1&query={{ $o->latitude }},{{ $o->longitude }}"
                                                        target="_blank">Link Gmaps</a></li>
                                                <li><a href="/setLocation/{{ $o->orderan->id }}">Update Lokasi</a></li>
                                            </ul>
                                        @else
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $o->latitude }},{{ $o->longitude }}"
                                                target="_blank">
                                                <span class="badge bg-success" style="cursor:pointer;">Lihat
                                                    di Gmaps</span>
                                            </a>
                                        @endif
                                    @else
                                        <a href="/setLocation/{{ $o->orderan->id }}" class="btn btn-sm btn-primary">Set
                                            Lokasi</a>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary status-toggle" style="cursor:pointer;"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#statusPembayaran{{ $o->orderan->id }}" aria-expanded="false"
                                        aria-controls="statusPembayaran{{ $o->orderan->id }}">
                                        Klik untuk melihat status
                                    </span>
                                    <div class="collapse mt-2" id="statusPembayaran{{ $o->orderan->id }}">
                                        <ul>
                                            @foreach ($o->orderan->statusPembayaran as $sp)
                                                <li>
                                                    <span class="badge bg-info"><b>{{ $sp->status }}</b></span><br>
                                                    {{ $sp->tgl }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary status-toggle" style="cursor:pointer;"
                                        data-bs-toggle="collapse" data-bs-target="#statusCucian{{ $o->orderan->id }}"
                                        aria-expanded="false" aria-controls="statusCucian{{ $o->orderan->id }}">
                                        Klik untuk melihat status
                                    </span>
                                    <div class="collapse mt-2" id="statusCucian{{ $o->orderan->id }}">
                                        <ul>
                                            @foreach ($o->orderan->statusCucian as $sc)
                                                <li>
                                                    <span class="badge bg-info"><b>{{ $sc->status }}</b></span><br>
                                                    {{ $sc->tgl }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="aksiDropdown{{ $o->id }}" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="aksiDropdown{{ $o->id }}">
                                            @if (
                                                $o->orderan->statusCucian->last()->status == 'Orderan Masuk' ||
                                                    $o->orderan->statusCucian->last()->status == 'Lokasi Jemput Diperbarui')
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="/batalkanOrder/{{ $o->orderan->id }}"
                                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan order ini?');">
                                                        <i class="fas fa-times-circle"></i> Batalkan Order
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($o->orderan->statusCucian->last()->status == 'Cucian Diantar')
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="/cucianSelesai/{{ $o->orderan->id }}"
                                                        onclick="return confirm('Apakah Anda yakin ingin menyelesaikan order ini?');">
                                                        <i class="fas fa-check-circle"></i> Cucian Selesai
                                                    </a>
                                                </li>
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
    </div>
    {{-- </div> --}}

    @include('customers.components.footer')

    <!-- Loader Spinner -->
    <div id="loader"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 9999; justify-content: center; align-items: center;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Scripts -->
    {{-- <script src="{{ asset('assets_customers/js/bootstrap.bundle.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets_customers/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets_customers/js/custom.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.metode-select').forEach(function(select) {
                select.addEventListener('change', function() {
                    let orderId = this.getAttribute('data-order-id');
                    let metode = this.value;
                    let form = document.getElementById('metodeForm' + orderId);
                    let token = form.querySelector('input[name="_token"]').value;

                    fetch('/setMetodePembayaran/' + orderId, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                metode_pembayaran: metode
                            })
                        })
                        .then(response => response.json())
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
    </script>

    <script>
        function bayar(id) {
            // Tampilkan loader
            $("#loader").css("display", "flex");

            $.ajax({
                url: "/transaksi/payment",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(response) {
                    $("#loader").hide();

                    snap.pay(response['snap_token'], {
                        onSuccess: function(result) {
                            window.location.href = 'transaksi/payment/success/' + response[
                                'snap_token'];
                        },
                        onPending: function(result) {
                            console.log("Pending:", result);
                        },
                        onError: function(result) {
                            console.log("Error:", result);
                            Swal.fire("Gagal!", "Transaksi gagal atau dibatalkan.", "error");
                        }
                    });
                },
                error: function(xhr) {
                    $("#loader").hide(); // Sembunyikan loader saat gagal
                    Swal.fire(
                        "Gagal!",
                        "Terjadi kesalahan saat memproses data.",
                        "error"
                    );
                }
            });
        }
    </script>
</body>

</html>
