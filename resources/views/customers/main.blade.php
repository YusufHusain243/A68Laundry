<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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

    @include('customers.components.hero')
    @include('customers.components.laundry')
    @include('customers.components.paket')
    @include('customers.components.whyChooseMe')
    @include('customers.components.statusCucian')
    @include('customers.components.footer')

    <!-- Loader Spinner -->
    <div id="loader"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 9999; justify-content: center; align-items: center;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script src="{{ asset('assets_customers/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_customers/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets_customers/js/custom.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script
        src='https://cdn.jotfor.ms/agent/embedjs/0197060efd357570ad7ee417a7fa255652d8/embed.js?skipWelcome=1&maximizable=1'>
    </script>

    <script>
        function bayar(id) {
            // Tampilkan loader
            $("#loader").css("display", "flex");

            $.ajax({
                url: "/paket/payment",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(response) {
                    $("#loader").hide(); // Sembunyikan loader sebelum snap muncul

                    snap.pay(response['snap_token'], {
                        onSuccess: function(result) {
                            $("#loader").css("display", "flex");
                            $.ajax({
                                url: '/paket/payment/success/' + response['snap_token'],
                                type: 'GET',
                                success: function(res) {
                                    window.location.href = '/paket/payment/success/' +
                                        response['snap_token'];
                                },
                                error: function(xhr) {
                                    $("#loader").hide();
                                    Swal.fire("Gagal!",
                                        "Terjadi kesalahan saat memproses data.",
                                        "error");
                                }
                            });
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

        $('#statusCucianForm').on('submit', function(e) {
            e.preventDefault();

            const orderCode = $('#orderCodeInput').val().trim();
            if (!orderCode) return;

            $('#statusCucianResult').html('');
            $('#loadingSpinner').show();

            $.ajax({
                url: '/cek-status-cucian',
                type: 'GET',
                data: {
                    order_code: orderCode
                },
                success: function(res) {
                    $('#loadingSpinner').hide();

                    if (res.success) {
                        const data = res.data;
                        const html = `
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode Order</th>
                                    <th>Jenis Laundry</th>
                                    <th>Status Cucian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong class="text-primary">${data.kode_order}</strong></td>
                                    <td><strong class="text-success">${data.jenis_laundry}</strong></td>
                                    <td><strong class="text-success">${data.status}</strong></td>
                                </tr>
                            </tbody>
                        </table>`;
                        $('#statusCucianResult').html(html);
                    } else {
                        $('#statusCucianResult').html(
                            `<div class="alert alert-warning">${res.message}</div>`);
                    }
                },
                error: function() {
                    $('#loadingSpinner').hide();
                    $('#statusCucianResult').html(
                        '<div class="alert alert-danger">Terjadi kesalahan. Silakan coba lagi.</div>'
                        );
                }
            });
        });
    </script>
</body>

</html>
