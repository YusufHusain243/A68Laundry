@extends('staffs.main')

@section('title', 'Orderan Offline')

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
                                KELOLA ORDERAN OFFLINE
                            </span>
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModalOrderOfflineLaundry">
                                Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orderOfflineLaundryTable" class="table table-striped">
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

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="addModalOrderOfflineLaundry" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="orderOfflineLaundryForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah/Edit Data Orderan Offline</h5>
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
                            <label for="berat" class="form-label">Berat</label>
                            <input type="number" step="0.1" class="form-control" id="berat" name="berat">
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Total Biaya</label>
                            <input type="text" class="form-control" id="harga" name="harga" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="metode_pembayaran" name="metode_pembayaran">
                                <option value="Cash">Tunai</option>
                                <option value="Transfer">Transfer</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let data = @json($orderan);
            let table = $('#orderOfflineLaundryTable').DataTable({
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
                    },
                    {
                        data: 'harga',
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
                                <button class="dropdown-item" type="button" onclick="cetakNota('${row.id}')">Cetak Nota</button>
                            `;
                            if (statusPembayaran === 'Belum Lunas') {
                                if (row.metode_pembayaran === 'Cash') {
                                    dropdownItems +=
                                        `<button class="dropdown-item" type="button" onclick="bayarCash('${row.id}')">Bayar (Cash)</button>`;
                                }

                                if (row.metode_pembayaran === 'Transfer') {
                                    dropdownItems +=
                                        `<button class="dropdown-item" type="button" onclick="bayarTransfer('${row.snap_token}')">Bayar (Transfer)</button>`;
                                }
                                dropdownItems += `
                                    <button class="dropdown-item" type="button" onclick="editData('${row.id}')">Edit</button>
                                    <button class="dropdown-item" type="button" onclick="hapusData('${row.id}')">Hapus</button>
                                `;
                            }

                            if (statusCucian === 'Sedang Dicuci') {
                                dropdownItems += `
                                    <button class="dropdown-item" type="button" onclick="cucianSelesai('${row.id}')">Proses Selesai</button>
                                `;
                            }

                            if (statusCucian === 'Cucian Selesai') {
                                dropdownItems += `
                                    <button class="dropdown-item" type="button" onclick="cucianDiambil('${row.id}')">Orderan Diambil</button>
                                `;
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
            window.orderOfflineLaundryTable = table;
        });

        function editData(id) {
            let table = window.orderOfflineLaundryTable;
            let data = table.data().toArray().find(row => String(row.id) === String(id));
            console.log(data);

            if (data) {
                $('#id').val(data.id);
                $('#nama').val(data.orderan_offline.nama);
                $('#no_hp').val(data.orderan_offline.no_hp);
                $('#email').val(data.orderan_offline.email);
                $('#alamat').val(data.orderan_offline.alamat);
                $('#jenis_laundry_id').val(data.jenis_laundry_id).trigger('change');
                $('#berat').val(data.berat);
                $('#harga').val(data.harga);
                $('#metode_pembayaran').val(data.metode_pembayaran).trigger('change');
                $('#addModalOrderOfflineLaundry').modal('show');
            }
        }

        function detailData(id) {
            let table = window.orderOfflineLaundryTable;
            let data = table.data().toArray().find(row => String(row.id) === String(id));
            console.log(data);

            if (data) {
                $('#id').val(data.id).prop('disabled', true);
                $('#nama').val(data.orderan_offline.nama).prop('disabled', true);
                $('#no_hp').val(data.orderan_offline.no_hp).prop('disabled', true);
                $('#email').val(data.orderan_offline.email).prop('disabled', true);
                $('#alamat').val(data.orderan_offline.alamat).prop('disabled', true);
                $('#jenis_laundry_id').val(data.jenis_laundry_id).prop('disabled', true).trigger('change');
                $('#berat').val(data.berat).prop('disabled', true);
                $('#harga').val(data.harga).prop('disabled', true);
                $('#metode_pembayaran').val(data.metode_pembayaran).prop('disabled', true).trigger('change');
                $('#addModalOrderOfflineLaundry').modal('show');
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

        function bayarCash(id){
            alert(
                'Konfirmasi Pembayaran', 
                'Apakah Anda yakin ingin melunasi pembayaran orderan ini?', 
                'question', 
                '/orderanOffline/bayarCash/',
                id
            );
        }

        function cucianSelesai(id){
            alert(
                'Konfirmasi Cucian Selesai', 
                'Apakah Anda yakin ingin menyelesaikan cucian ini?', 
                'question', 
                '/orderanOffline/cucianSelesai/',
                id
            );
        }

        function cucianDiambil(id){
            alert(
                'Konfirmasi Cucian Diambil', 
                'Apakah Anda yakin ingin mengkonfirmasi cucian diambil?', 
                'question', 
                '/orderanOffline/cucianDiambil/',
                id
            );
        }

        function hapusData(id) {
            alert(
                'Konfirmasi Hapus Data', 
                'Apakah Anda yakin ingin menghapus data ini?', 
                'warning', 
                '/orderanOffline/destroy/',
                id
            );
        }

        function cetakNota(id) {
            window.open('/orderanOffline/cetakNota/' + id, '_blank');
        }

        function bayarTransfer(id) {
            snap.pay(id, {
                onSuccess: function(result) {
                    window.location.href = 'orderanOffline/bayar/success/' + id;
                },
                onPending: function(result) {
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                },
                onError: function(result) {
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                }
            });
        }

        $('#addModalOrderOfflineLaundry').on('show.bs.modal', function() {
            let id = $('#id').val();
            let form = $('#orderOfflineLaundryForm');
            if (id) {
                form.attr('action', '/orderanOffline/update');
            } else {
                form.attr('action', '/orderanOffline');
            }
        });

        $('#addModalOrderOfflineLaundry').on('hidden.bs.modal', function() {
            $('#orderOfflineLaundryForm').attr('action', '/orderanOffline');
            $('#orderOfflineLaundryForm')[0].reset();
            $('#id').val('');
        });

        document.addEventListener('DOMContentLoaded', function() {
            const beratInput = document.getElementById('berat');
            const jenisLaundrySelect = document.getElementById('jenis_laundry_id');
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
            jenisLaundrySelect.addEventListener('change', hitungTotalBiaya);
        });
    </script>
@endpush
