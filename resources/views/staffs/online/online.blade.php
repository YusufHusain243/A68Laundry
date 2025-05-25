@extends('staffs.main')

@section('title', 'Dashboard')

@section('content')
    <div class="pc-content">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                 @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <span class="d-flex align-items-center">
                                KELOLA ORDERAN ONLINE
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orderOnlineLaundryTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Order</th>
                                        <th>Jenis Laundry</th>
                                        <th>Berat</th>
                                        <th>Harga</th>
                                        <th>Pembayaran</th>
                                        <th>Status Pembayaran</th>
                                        <th>Status Cucian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalInputTimbangan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/orderanOnline/inputTimbangan" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Masukkan Timbangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="jenisLaundryId" id="jenisLaundryId">
                        <div class="mb-3">
                            <label for="jenis_laundry" class="form-label">Jenis Laundry</label>
                            <input type="text" class="form-control" id="jenis_laundry" name="jenis_laundry" disabled readonly>
                        </div>
                        <div class="mb-3">
                            <label for="berat" class="form-label">Berat</label>
                            <input type="text" class="form-control" id="berat" name="berat">
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="harga" name="harga">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetalOrderOnline" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Orderan Online</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama">
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No.HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat">
                    </div>
                    <div class="mb-3">
                        <label for="jenis_laundry_id" class="form-label">Jenis Laundry</label>
                        <select class="form-select" id="jenis_laundry_id" name="jenis_laundry_id">
                            <option disabled selected>Pilih Jenis Laundry</option>
                            @foreach ($jenisLaundry as $jl)
                                <option value="{{ $jl->id }}">{{ $jl->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jarak" class="form-label">Jarak</label>
                        <input type="text"class="form-control" id="jarak" name="jarak">
                    </div>
                    <div class="mb-3">
                        <label for="ongkir" class="form-label">Ongkir</label>
                        <input type="text" class="form-control" id="ongkir" name="ongkir">
                    </div>
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label><br>
                        <a href="#" id="lokasi" class="btn btn-primary">Klik Untuk Melihat Lokasi</a>
                    </div>
                    <div class="mb-3">
                        <label for="berat_detail" class="form-label">Berat</label>
                        <input type="number" step="0.1" class="form-control" id="berat_detail" name="berat_detail">
                    </div>
                    <div class="mb-3">
                        <label for="harga_detail" class="form-label">Total Biaya</label>
                        <input type="text" class="form-control" id="harga_detail" name="harga_detail">
                    </div>
                    <div class="mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="metode_pembayaran" name="metode_pembayaran">
                            <option value="Cash">Tunai</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let data = @json($orderan);

            // Inisialisasi DataTable dan simpan instance-nya
            let table = $('#orderOnlineLaundryTable').DataTable({
                data: data,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'kode_order',
                    },
                    {
                        data: 'jenis_laundry.nama'
                    },
                    {
                        data: 'berat',
                        defaultContent: '0',
                    },
                    {
                        data: 'harga',
                        defaultContent: '0',
                    },
                    {
                        data: 'metode_pembayaran'
                    },
                    {
                        data: 'status_pembayaran',
                        render: function(data, type, row) {
                            let statusList = '<ul>';
                            data.forEach(function(status) {
                                 statusList += '<li><strong>' + String(status.status) + '</strong><br>' + String(status.tgl) + '</li>'; // Added missing concatenation operator (+) after status.tgl
                            });
                            statusList += '</ul>';
                            return statusList;
                        }
                    },
                    {
                        data: 'status_cucian',
                        render: function(data, type, row) {
                            let statusList = '<ul>';
                            data.forEach(function(status) {
                                statusList += '<li><strong>' + String(status.status) + '</strong><br>' + String(status.tgl) + '</li>'; // Added missing concatenation operator (+) after status.tgl
                            });
                            statusList += '</ul>';
                            return statusList;
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            let statusCucian = row.status_cucian[row.status_cucian.length - 1].status;
                            let statusPembayaran = row.status_pembayaran[row.status_pembayaran.length - 1].status;
                            let dropdownItems = '';

                            dropdownItems += `
                                <button class="dropdown-item" type="button" onclick="detailData('${row.id}')">Detail</button>
                            `;

                            if (statusCucian === 'Menunggu Cucian Diambil') {
                                dropdownItems +=
                                    `<button class="dropdown-item" type="button" onclick="ambilCucian('${row.id}')">Ambil Cucian</button>`;
                            }
                            
                            if (statusCucian === 'Cucian Diambil') {
                                dropdownItems +=
                                    `<button class="dropdown-item" type="button" onclick="inputTimbangan('${row.id}')">Input Timbangan</button>`;
                            }
                            
                            if (statusCucian === 'Sedang Dicuci') {
                                dropdownItems +=
                                    `<button class="dropdown-item" type="button" onclick="cuciSelesai('${row.id}')">Cuci Selesai</button>`;
                            }

                            if (statusCucian === 'Cucian Selesai') {
                                dropdownItems +=
                                    `<button class="dropdown-item" type="button" onclick="antarCucian('${row.id}')">Antar Cucian</button>`;
                            }

                            return `
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <div class="dropdown-menu">
                                        ${dropdownItems}
                                    </div>
                                </div>
                            `;
                        }
                    }
                ]
            });
            window.orderOnlineLaundryTable = table;
        });

        function detailData(id) {
            let table = window.orderOnlineLaundryTable;
            let data = table.data().toArray().find(row => String(row.id) === String(id));
            console.log(data);

            if (data) {
                $('#id').val(data.id).prop('disabled', true);
                $('#nama').val(data.orderan_online.user.nama).prop('disabled', true);
                $('#no_hp').val(data.orderan_online.user.no_hp).prop('disabled', true);
                $('#email').val(data.orderan_online.user.email).prop('disabled', true);
                $('#alamat').val(data.orderan_online.user.alamat).prop('disabled', true);
                $('#jenis_laundry_id').val(data.jenis_laundry_id).prop('disabled', true).trigger('change');
                $('#jarak').val(data.orderan_online.jarak).prop('disabled', true);
                $('#ongkir').val(data.orderan_online.ongkir).prop('disabled', true);
                $('#lokasi').attr('href', 'https://www.google.com/maps/search/?api=1&query=' + data.orderan_online.latitude + ',' + data.orderan_online.longitude);
                $('#berat_detail').val(data.berat).prop('disabled', true);
                $('#harga_detail').val(data.harga).prop('disabled', true);
                $('#metode_pembayaran').val(data.metode_pembayaran).prop('disabled', true).trigger('change');
                $('#modalDetalOrderOnline').modal('show');
            }
        }

        function alert(label, desc, icon, url, id){
            Swal.fire({
                title: label,
                text: desc,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url + id,
                        type: "POST",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    "Berhasil!",
                                    response.message,
                                    "success"
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    "Gagal!",
                                    response.message,
                                    "error"
                                );
                            }
                        },
                        error: function(xhr) {
                            let msg = "Gagal menghapus data. Silakan coba lagi.";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire(
                                "Gagal!",
                                msg,
                                "error"
                            );
                        }
                    });
                }
            });
        }

        function ambilCucian(id){
            alert(
                'Konfirmasi Ambil Cucian', 
                'Apakah Anda yakin ingin mengambil cucian ini?', 
                'question', 
                '/orderanOnline/ambilCucian/', 
                id
            );
        }

        function inputTimbangan(id) {
            let table = window.orderOnlineLaundryTable;
            let data = table.data().toArray().find(row => String(row.id) === String(id));
            if (data) {
                $('#id').val(data.id);
                $('#jenis_laundry').val(data.jenis_laundry.nama);
                $('#jenisLaundryId').val(data.jenis_laundry.id);
                $('#modalInputTimbangan').modal('show');
            }
        }

        function cuciSelesai(id){
            alert(
                'Konfirmasi Cucian Selesai', 
                'Apakah Anda yakin ingin menyelesaikan cucian ini?', 
                'question', 
                '/orderanOnline/cuciSelesai/', 
                id
            );
        }
        
        function antarCucian(id){
            alert(
                'Konfirmasi Antar Cucian', 
                'Apakah Anda yakin ingin mengantar cucian ini?', 
                'question', 
                '/orderanOnline/antarCucian/', 
                id
            );
        }

        document.addEventListener('DOMContentLoaded', function() {
            const beratInput = document.getElementById('berat');
            const jenisLaundrySelect = document.getElementById('jenisLaundryId');
            const hargaInput = document.getElementById('harga');

            const hargaJenisLaundry = {
                @foreach ($jenisLaundry as $jl)
                    "{{ $jl->id }}": {{ $jl->harga }},
                @endforeach
            };

            function hitungTotalBiaya() {
                const berat = parseFloat(beratInput.value) || 0;
                const jenisId = jenisLaundrySelect.value;
                const hargaPerKg = hargaJenisLaundry[jenisId] || 0;
                hargaInput.value = berat > 0 && hargaPerKg > 0 ? berat * hargaPerKg : '';
            }

            beratInput.addEventListener('input', hitungTotalBiaya);
        });
    </script>
@endpush
