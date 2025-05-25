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
                                KELOLA PAKET LAUNDRY
                            </span>
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModalPaketLaundryMember">
                                Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="paketLaundryMember" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Laundry</th>
                                        <th>KG</th>
                                        <th>Terpakai KG</th>
                                        <th>Sisa KG</th>
                                        <th>Harga</th>
                                        <th>Status Pembayaran</th>
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

    <div class="modal fade" id="addModalPaketLaundryMember" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="paketLaundryMemberForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah/Edit Data Paket Laundry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="paket_laundry_id" class="form-label">Paket</label>
                            <select class="form-select" id="paket_laundry_id" name="paket_laundry_id">
                                <option disabled selected>Pilih Paket Laundry</option>
                                @foreach ($paketLaundry as $pl)
                                    <option value="{{ $pl->id }}">{{ $pl->jenisLaundry->nama }} - Rp.
                                        {{ $pl->harga }}/{{ $pl->berat }} KG</option>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let data = @json($paketMember);
            let table = $('#paketLaundryMember').DataTable({
                data: data,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'paket_laundry.jenis_laundry.nama',
                    },
                    {
                        data: 'paket_laundry.berat'
                    },
                    {
                        data: 'kg_terpakai',
                    },
                    {
                        data: 'kg_sisa',
                    },
                    {
                        data: 'paket_laundry.harga'
                    },
                    {
                        data: 'status_pembayaran'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            if (row.status_pembayaran === 'Belum Lunas') {
                                return `<button type="button" class="btn btn-sm btn-info" onclick="editData('${row.id}')">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="hapusData('${row.id}')">Hapus</button>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="bayar('${row.snap_token}')">Bayar</button>`;
                            } else {
                                return '';
                            }
                        }
                    }
                ]
            });

            window.paketLaundryMember = table;
        });

        function editData(id) {
            let table = window.paketLaundryMember;
            let data = table.data().toArray().find(row => String(row.id) === String(id));
            if (data) {
                $('#id').val(data.id);
                $('#paket_laundry_id').val(data.paket_laundry_id).trigger('change');
                $('#addModalPaketLaundryMember').modal('show');
            }
        }

        function hapusData(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/paketLaundryMember/destroy/" + id,
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

        function bayar(id) {
            snap.pay(id, {
                onSuccess: function(result) {
                    window.location.href = 'paketLaundryMember/bayar/success/' + id;
                },
                onPending: function(result) {
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                },
                onError: function(result) {
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                }
            });
        }

        $('#addModalPaketLaundryMember').on('show.bs.modal', function(event) {
            let id = $('#id').val();
            let form = $('#paketLaundryMemberForm');
            if (id) {
                form.attr('action', '/paketLaundryMember/update');
            } else {
                form.attr('action', '/paketLaundryMember');
            }
        });
        $('#addModalPaketLaundryMember').on('hidden.bs.modal', function() {
            $('#paketLaundryMemberForm').attr('action', '/paketLaundryMember');
            $('#paketLaundryMemberForm')[0].reset();
            $('#id').val('');
        });
    </script>
@endpush
