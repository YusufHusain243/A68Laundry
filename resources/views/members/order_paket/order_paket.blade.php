@extends('members.main')

@section('title', 'Paket Laundry')

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
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <span class="d-flex align-items-center">
                                PAKET LAUNDRY ANDA
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Laundry</th>
                                        <th>KG</th>
                                        <th>Terpakai KG</th>
                                        <th>Sisa KG</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paketLaundry as $pl)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pl->paketLaundry->jenisLaundry->nama }}</td>
                                            <td>{{ $pl->paketLaundry->berat }}</td>
                                            <td>{{ $pl->kg_terpakai }}</td>
                                            <td>{{ $pl->kg_sisa }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <span class="d-flex align-items-center">
                                KELOLA ORDERAN PAKET
                            </span>
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModalOrderPaket">
                                Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="orderPaketTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Order</th>
                                        <th>Jenis Laundry</th>
                                        <th>Total Pembayaran</th>
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

    <div class="modal fade" id="addModalOrderPaket" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="orderPaketForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah/Edit Orderan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="jenis_laundry_id" class="form-label">Pilih Paket Laundry Anda</label>
                            <select class="form-select" id="jenis_laundry_id" name="jenis_laundry_id">
                                <option disabled selected>Pilih Paket Laundry</option>
                                @foreach ($paketLaundry as $pl)
                                    <option value="{{ $pl->paketLaundry->jenisLaundry->id }}">{{ $pl->paketLaundry->jenisLaundry->nama }}</option>
                                @endforeach
                            </select>
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

    <div class="modal fade" id="detailModalOrderPaket" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Orderan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_order_detail" class="form-label">Kode Order</label>
                        <input type="text" class="form-control" id="kode_order_detail" name="kode_order_detail">
                    </div>
                    <div class="mb-3">
                        <label for="jenis_laundry_id_detail" class="form-label">Jenis Laundry</label>
                        <select class="form-select" id="jenis_laundry_id_detail" name="jenis_laundry_id_detail">
                            <option disabled selected>Pilih Jenis Laundry</option>
                            @foreach ($jenisLaundry as $jl)
                                <option value="{{ $jl->id }}">{{ $jl->nama }} - Rp.
                                    {{ $jl->harga }}/{{ $jl->berat }} KG</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="berat_detail" class="form-label">Berat</label>
                        <input type="text" class="form-control" id="berat_detail" name="berat_detail">
                    </div>
                    <div class="mb-3">
                        <label for="harga_detail" class="form-label">Harga</label>
                        <input type="text" class="form-control" id="harga_detail" name="harga_detail">
                    </div>
                    <div class="mb-3">
                        <label for="jarak_detail" class="form-label">Jarak</label>
                        <input type="text" class="form-control" id="jarak_detail" name="jarak_detail">
                    </div>
                    <div class="mb-3">
                        <label for="ongkir_detail" class="form-label">Ongkir</label>
                        <input type="text" class="form-control" id="ongkir_detail" name="ongkir_detail">
                    </div>
                    <div class="mb-3">
                        <label for="total_pembayaran_detail" class="form-label">Total Pembayaran</label>
                        <input type="text" class="form-control" id="total_pembayaran_detail" name="total_pembayaran_detail">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let data = @json($orderan);
            let table = $('#orderPaketTable').DataTable({
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
                        data: 'jenis_laundry.nama',
                    },
                    {
                        data: 'orderan_online.ongkir',
                        render: function(data, type, row) {
                            let totalCost = parseInt(data) + parseInt(row.harga);
                            return 'Rp. ' + totalCost.toLocaleString();
                        }
                    },
                    {
                        data: 'status_pembayaran',
                        render: function(data, type, row) {
                            let statusList = '<ul>';
                            data.forEach(function(status) {
                                statusList += '<li><strong>' + String(status.status) +
                                    '</strong><br>' + String(status.tgl) +
                                    '</li>';
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
                                statusList += '<li><strong>' + String(status.status) +
                                    '</strong><br>' + String(status.tgl) +
                                    '</li>';
                            });
                            statusList += '</ul>';
                            return statusList;
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            let statusCucian = row.status_cucian[row.status_cucian.length - 1]
                                .status;
                            let statusPembayaran = row.status_pembayaran[row.status_pembayaran
                                .length - 1].status;
                            let dropdownItems = '';

                            dropdownItems += `
                                <button class="dropdown-item" type="button" onclick="detailData('${row.id}')">Detail</button>
                                <button class="dropdown-item" type="button" onclick="cetakNota('${row.id}')">Cetak Nota</button>
                            `;
                            if (statusCucian === 'Menunggu Set Lokasi') {
                                dropdownItems +=
                                    `<button class="dropdown-item" type="button" onclick="setLocation('${row.id}')">Set Lokasi Jemput</button>`;
                            }
                            if(statusCucian === 'Menunggu Set Lokasi' || statusCucian === 'Menunggu Cucian Diambil'){
                                dropdownItems += `
                                    <button class="dropdown-item" type="button" onclick="editData('${row.id}')">Edit</button>
                                    <button class="dropdown-item" type="button" onclick="hapusData('${row.id}')">Hapus</button>
                                `;
                            }

                            if (statusCucian === 'Cucian Diantar') {
                                dropdownItems +=
                                    `<button class="dropdown-item" type="button" onclick="selesai('${row.id}')">Order Selesai</button>`;
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
            window.orderPaketTable = table;
        });

        function detailData(id) {
            let table = window.orderPaketTable;
            let data = table.data().toArray().find(row => String(row.id) === String(id));
            console.log(data);

            if (data) {
                $('#kode_order_detail').val(data.kode_order).prop('disabled', true);
                $('#jenis_laundry_id_detail').val(data.jenis_laundry_id).prop('disabled', true).trigger('change');
                $('#berat_detail').val(data.berat).prop('disabled', true);
                $('#harga_detail').val(data.harga).prop('disabled', true);
                $('#jarak_detail').val(data.orderan_online.jarak).prop('disabled', true);
                $('#ongkir_detail').val(data.orderan_online.ongkir).prop('disabled', true);
                $('#total_pembayaran_detail').val(parseInt(data.harga) + parseInt(data.orderan_online.ongkir)).prop('disabled', true);
                $('#detailModalOrderPaket').modal('show');
            }
        }

        function editData(id) {
            let table = window.orderPaketTable;
            let data = table.data().toArray().find(row => String(row.id) === String(id));
            if (data) {
                $('#id').val(data.id);
                $('#jenis_laundry_id').val(data.jenis_laundry.id).trigger('change');
                $('#addModalOrderPaket').modal('show');
            }
        }

        function setLocation(id) {
            window.location.href = '/orderPaket/setLocation/' + id;
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

        function hapusData(id){
            alert(
                'Konfirmasi Hapus Data', 
                'Apakah Anda yakin ingin menghapus data ini?', 
                'warning', 
                '/orderPaket/destroy/', 
                id
            );
        }

        function bayar(id) {
            $.ajax({
                url: "/orderLangsung/bayarOrderan",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(response) {
                    snap.pay(response.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = 'orderLangsung/bayarOrderan/success/' + response
                                .snap_token;
                        },
                        onPending: function(result) {
                            document.getElementById('result-json').innerHTML += JSON
                                .stringify(
                                    result, null, 2);
                        },
                        onError: function(result) {
                            document.getElementById('result-json').innerHTML += JSON
                                .stringify(
                                    result, null, 2);
                        }
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        "Gagal!",
                        "Terjadi kesalahan saat memproses data.",
                        "error"
                    );
                }
            });
        }

        function selesai(id) {
            alert(
                'Konfirmasi Selesai', 
                'Apakah Anda yakin ingin menyelesaikan order ini?', 
                'warning', 
                '/orderPaket/selesai/', 
                id
            );
        }

        function cetakNota(id) {
            window.open('/orderPaket/cetakNota/' + id, '_blank');
        }

        $('#addModalOrderPaket').on('show.bs.modal', function(event) {
            let id = $('#id').val();
            let form = $('#orderPaketForm');
            if (id) {
                form.attr('action', '/orderPaket/update');
            } else {
                form.attr('action', '/orderPaket');
            }
        });
        
        $('#addModalOrderPaket').on('hidden.bs.modal', function() {
            $('#orderPaketForm').attr('action', '/orderPaket');
            $('#orderPaketForm')[0].reset();
            $('#id').val('');
        });
    </script>
@endpush
