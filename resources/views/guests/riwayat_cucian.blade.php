<!doctype html>
<html lang="en" dir="ltr" data-bs-theme="auto">

<head>
    <script src="{{ asset('assets_customer/js/color-modes.js') }}"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="tigmatemplate">
    <meta name="generator" content="Bootstrap">
    <title>E-Laundry - A68</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets_customer/logo/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets_customer/logo/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets_customer/logo/favicon-16x16.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets_customer/logo/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('assets_customer/logo/site.webmanifest') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets_customer/libraries/glide/css/glide.core.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_customer/libraries/aos/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_customer/css/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_customer/css/style.css') }}">
</head>

<body>
    @include('guests.components.navbar')
    @include('guests.components.banner2')

    <div class="container mt-5">
        <h2>Riwayat Cucian</h2>
        <div class="mt-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Search..." aria-label="Search"
                style="width: 250px;">
        </div>

        <table class="table table-striped mt-3" id="tableRiwayatCucian">
            <thead>
                <tr>
                    <th scope="col">Nomor Orderan</th>
                    <th scope="col">Jenis Laundry</th>
                    <th scope="col">Berat</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $cucian)
                    <tr>
                        <td>{{ $cucian->kode_order }}</td>
                        <td>{{ $cucian->jenisLaundry->nama }}</td>
                        <td>{{ $cucian->berat }} kg</td>
                        <td>Rp. {{ number_format($cucian->harga, 0, ',', '.') }}</td>
                        <td>
                            <ul class="mb-0 ps-3">
                                @foreach ($cucian->statusCucian as $status)
                                    <li>
                                        {{ $status->status }}<br>
                                        {{ \Carbon\Carbon::parse($status->tgl)->format('d M Y H:i') }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('guests.components.footer')

    <!-- Back to top button -->
    <button type="button"
        class="btn btn-primary btn-back-to-top rounded-circle justify-content-center align-items-center p-2 text-white">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-caret-up-fill" viewBox="0 0 16 16">
            <path
                d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z" />
        </svg>
    </button>

    <!-- Script Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets_customer/libraries/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_customer/libraries/glide/glide.min.js') }}"></script>
    <script src="{{ asset('assets_customer/libraries/aos/aos.js') }}"></script>
    <script src="{{ asset('assets_customer/js/scripts.js') }}"></script>

     <script
        src='https://cdn.jotfor.ms/agent/embedjs/0197060efd357570ad7ee417a7fa255652d8/embed.js?skipWelcome=1&maximizable=1'>
    </script>

    <!-- Table Search Script -->
    <script>
        $(document).ready(function () {
            $('#searchInput').on('keyup', function () {
                let value = $(this).val().toLowerCase();
                $('#tableRiwayatCucian tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
