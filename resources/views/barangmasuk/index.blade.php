@extends('layouts.master')

@section('title')
    Data Barang Masuk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Data Barang Masuk</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="showPeriodeModal()" class="btn btn-primary btn-xsbtn-flat"><i class="fa fa-plus-circle"></i> Periode</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Tanggal Masuk</th>
                        <th>Jumlah Masuk</th>
                        <th>Penanggung Jawab</th>
                        <th width="10%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk memilih rentang waktu -->
<div class="modal fade" id="periodeModal" tabindex="-1" role="dialog" aria-labelledby="periodeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="periodeModalLabel">Pilih Periode Waktu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="periodeForm">
                    <div class="form-group">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" id="start_date" class="form-control" required
                            oninvalid="this.setCustomValidity('Tanggal harus diisi.')"
                             oninput="this.setCustomValidity('')">
                    </div>
                    <div class="form-group">
                        <label for="end_date">Tanggal Selesai</label>
                        <input type="date" id="end_date" class="form-control" required
                            oninvalid="this.setCustomValidity('Tanggal harus diisi.')"
                            oninput="this.setCustomValidity('')">
                    </div>
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('barangmasuk.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        // Initialize DataTable
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('barangmasuk.data') }}',
                data: function (d) {
                    // Send additional data (start_date and end_date) to the server
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_produk'},
                {data: 'nama_kategori'},
                {data: 'tanggal'},
                {data: 'jumlahMasuk'},
                {data: 'nama'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });
    });

    // Function to show Periode Modal
    function showPeriodeModal() {
        $('#periodeModal').modal('show');
    }

    // Handle form submission for filtering data by date range
    $('#periodeForm').on('submit', function (e) {
        e.preventDefault();

        const start = $('#start_date').val();
        const end = $('#end_date').val();

        // Validasi: tanggal akhir tidak boleh lebih kecil dari tanggal awal
        if (start && end && end < start) {
            Swal.fire({
                icon: 'error',
                title: 'Tanggal Tidak Valid',
                text: 'Tanggal awal tidak boleh melebihi tanggal akhir.',
                confirmButtonText: 'OK'
            });
            return; // Hentikan submit
        }

        // Jika valid, reload DataTable dan tutup modal
        table.ajax.reload();
        $('#periodeModal').modal('hide');
    });


    function deleteData(url) {
        // Show SweetAlert2 confirmation modal before deletion
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan bisa mengembalikan data yang dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            iconColor: '#28a745',  // Change the color of the icon
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
                swalTitle.style.fontSize = '60px';  // Larger title font size
                swalContent.style.fontSize = '25px';  // Larger content font size
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with deletion if confirmed
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload();
                        // Show success message after deletion
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Barang Masuk berhasil dihapus',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#4f9b8f',
                            showConfirmButton: true,
                        });
                    })
                    .fail((xhr) => {
                        let errorMessage = 'Data tidak dapat dihapus karena stok sudah digunakan.';
                        
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorMessage,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#d33',
                        });
                    });

            }
        });
    }

</script>
@endpush
