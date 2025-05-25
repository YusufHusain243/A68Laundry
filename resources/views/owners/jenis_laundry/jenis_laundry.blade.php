@extends('owners.main')

@section('title', 'Jenis Laundry')

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
                                KELOLA JENIS LAUNDRY
                            </span>
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModalJenisLaundryOwner">
                                Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="jenisLaundryOwnerTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Berat</th>
                                        <th>Deskripsi</th>
                                        <th>Foto</th>
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
    <div class="modal fade" id="addModalJenisLaundryOwner" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="jenisLaundryOwnerForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah/Edit Data Jenis Laundry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama">
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga">
                        </div>
                        <div class="mb-3">
                            <label for="berat" class="form-label">Berat</label>
                            <input type="text" class="form-control" id="berat" name="berat">
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi">
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto">
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
            let data = @json($jenisLaundry);

            $('#jenisLaundryOwnerTable').DataTable({
                data: data,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'harga'
                    },
                    {
                        data: 'berat'
                    },
                    {
                        data: 'deskripsi'
                    },
                    {
                        data: 'foto',
                        render: function(data, type, row) {
                            return `<img src="/images/${data}" alt="Foto Jenis Laundry" style="max-width: 100px;">`;
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `<button class='btn btn-sm btn-info' onclick='editData("${data}")'>Edit</button>
                                    <button class='btn btn-sm btn-danger' onclick='hapusData("${data}")'>Hapus</button>`;
                        }
                    }
                ]
            });
        });

        function editData(id) {
            let table = $('#jenisLaundryOwnerTable').DataTable();
            let data = table.data().toArray().find(row => String(row.id) === String(id));
            if (data) {
                $('#nama').val(data.nama);
                $('#harga').val(data.harga);
                $('#berat').val(data.berat);
                $('#deskripsi').val(data.deskripsi);
                $('#foto').next('.custom-file-label').html(data.foto);
                $('#id').val(data.id);
                $('#addModalJenisLaundryOwner').modal('show');
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
                        url: "/jenisLaundryOwner/destroy/" + id,
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

        // Ganti action form sesuai id
        $('#addModalJenisLaundryOwner').on('show.bs.modal', function(event) {
            let id = $('#id').val();
            let form = $('#jenisLaundryOwnerForm');
            if (id) {
                form.attr('action', '/jenisLaundryOwner/update');
            } else {
                form.attr('action', '/jenisLaundryOwner');
            }
        });
        // Reset action saat modal ditutup
        $('#addModalJenisLaundryOwner').on('hidden.bs.modal', function() {
            $('#jenisLaundryOwnerForm').attr('action', '/jenisLaundryOwner');
            $('#jenisLaundryOwnerForm')[0].reset();
            $('#id').val('');
        });
    </script>
@endpush
