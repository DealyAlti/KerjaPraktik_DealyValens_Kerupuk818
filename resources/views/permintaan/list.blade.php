@extends('layouts.master')

@section('title')
    Daftar Permintaan Barang
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Lihat Permintaan</li>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Daftar Permintaan Barang</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped" id="permintaanTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Jumlah</th> 
                            <th>Tanggal</th>
                            <th>Tindak Lanjut</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data permintaan barang akan diisi oleh DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var table; 
    $(document).ready(function() {
            table = $('#permintaanTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('permintaan.data') }}',
                type: 'GET',
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'user' },  
                { data: 'produk.nama_produk' }, 
                { data: 'kategori' },  
                { data: 'jumlahPermintaan' },  
                { data: 'tanggal_permintaan' },  
                {
                    data: 'status',
                    render: function(data, type, row) {
                        const level = {{ auth()->user()->level }}; // Ambil level user saat ini dari server-side

                        if (data === 'Disetujui' || data === 'Ditolak' || data === 'Batal') {
                            return '<label>Selesai</label>';
                        }

                        let buttons = `<form action="{{ route('permintaan.updateStatus', '') }}/${row.id_permintaanbarang}" method="POST" style="display:inline;">@csrf`;

                        // Tombol Setuju dan Tolak hanya untuk level 0 dan 1
                        if (level === 1) {
                            buttons += `
                                <button type="submit" class="btn btn-success" name="status" value="disetujui">Setuju</button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal${row.id_permintaanbarang}">Tolak</button>
                            `;
                        }

                        // Tombol Batal hanya untuk level 0 dan 2
                        if (level === 2) {
                            buttons += `
                                <button type="button" class="btn btn-warning" onclick="cancelRequest(${row.id_permintaanbarang})">Batal</button>
                            `;
                        }

                        buttons += `</form>`;

                        // Modal Tolak tetap ditampilkan untuk semua, karena bisa muncul jika tombolnya diklik
                        buttons += `
                            <!-- Modal untuk alasan penolakan -->
                            <div class="modal fade" id="rejectModal${row.id_permintaanbarang}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel${row.id_permintaanbarang}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="showReasonModalLabel${row.id_permintaanbarang}" style="font-weight: bold;">Alasan Penolakan</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('permintaan.updateStatus', '') }}/${row.id_permintaanbarang}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="ditolak">
                                                <div class="form-group" style="width: 100%;">
                                                    <textarea class="form-control" name="alasan_penolakan" rows="5" style="width: 100%;" required></textarea>
                                                </div>
                                                <br></br>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-danger btn-block" style="width: 100%;">Kirim Alasan Penolakan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        return buttons;
                    },
                    searchable: false,
                    sortable: false,
                },
                { 
                    data: 'status'
                },
                    // Kolom alasan penolakan yang baru ditambahkan
                {
                    data: 'alasan_penolakan',
                        render: function(data, type, row) {
                            if (data) {
                                return `
                                    <button type="button" class="btn-xs btn-info" data-toggle="modal" data-target="#showReasonModal${row.id_permintaanbarang}">
                                        <i class="fa fa-eye"></i> <!-- Ikon mata -->
                                    </button>
                                    <!-- Modal untuk menampilkan alasan penolakan -->
                                    <div class="modal fade" id="showReasonModal${row.id_permintaanbarang}" tabindex="-1" role="dialog" aria-labelledby="showReasonModalLabel${row.id_permintaanbarang}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="showReasonModalLabel${row.id_permintaanbarang}" style="font-weight: bold;">Alasan Penolakan</h4>
                                                </div>
                                                <div class="modal-body">
                                                    ${data}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                `;
                            } else {
                                return '-'; // Jika tidak ada alasan penolakan, tampilkan '-'
                            }
                        },
                        searchable: false,
                        sortable: false,
                }
            ]
        });

        // Menangani pesan error atau sukses
        @if(session('success'))
            setTimeout(function() {
                $('.alert-success').fadeOut();
            }, 5000); // Pesan sukses hilang setelah 5 detik
        @endif

        @if(session('error'))
            setTimeout(function() {
                $('.alert-danger').fadeOut();
            }, 5000); // Pesan error hilang setelah 5 detik
        @endif
    });
    function cancelRequest(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Permintaan akan dibatalkan dan tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, batalkan!',
            cancelButtonText: 'Batal',
            iconColor: '#ffc107', // warna ikon kuning (seperti warna peringatan)
            customClass: {
                popup: 'swal-popup-large',
                title: 'swal-title-large',
                content: 'swal-content-large',
                confirmButton: 'swal-button-large',
                cancelButton: 'swal-button-large',
            },
            didOpen: () => {
                const swalTitle = document.querySelector('.swal-title');
                const swalContent = document.querySelector('.swal-content');
                if (swalTitle) swalTitle.style.fontSize = '30px';
                if (swalContent) swalContent.style.fontSize = '18px';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('permintaan.cancelRequest', ':id') }}'.replace(':id', id),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: 'batal'
                    },
                    success: function(response) {
                        table.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#4f9b8f',
                            showConfirmButton: true,
                        });
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat membatalkan permintaan',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#d33',
                        });
                    }
                });
            }
        });
    }

</script>
@endpush
