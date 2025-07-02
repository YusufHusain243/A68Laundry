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
                        <h1>Keranjang</h1>
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
                <form class="col-md-12" method="POST" action="/keranjang/checkout" onsubmit="return validateForm()">
                    @csrf
                    <div class="site-blocks-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll"></th>
                                    <th class="product-thumbnail">Gambar</th>
                                    <th class="product-name">Jenis Laundry</th>
                                    <th class="product-price">Harga/KG</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keranjangs as $keranjang)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="keranjang_ids[]" value="{{ $keranjang->id }}"
                                                class="item-checkbox">
                                        </td>
                                        <td class="product-thumbnail">
                                            <img src="{{ asset('images/' . $keranjang->jenisLaundry->foto) }}"
                                                alt="Image" class="img-fluid">
                                        </td>
                                        <td class="product-name">
                                            <h2 class="h5 text-black">{{ $keranjang->jenisLaundry->nama }}</h2>
                                        </td>
                                        <td>{{ $keranjang->jenisLaundry->harga }}/KG</td>
                                        <td>
                                            <a href="/keranjang/destroy/{{ $keranjang->id }}"
                                                class="btn btn-black btn-sm">X</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-12">
                            <button id="checkoutButton" type="submit" class="btn btn-black btn-lg py-3 btn-block">
                                Checkout
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('customers.components.footer')

    <!-- Scripts -->
    <script src="{{ asset('assets_customers/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_customers/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets_customers/js/custom.js') }}"></script>

    <script>
        // Checkbox Select All
        document.getElementById('checkAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Validasi sebelum submit
        function validateForm() {
            const checkedItems = document.querySelectorAll('.item-checkbox:checked');
            if (checkedItems.length === 0) {
                alert('Silakan pilih minimal 1 item untuk melanjutkan checkout.');
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
