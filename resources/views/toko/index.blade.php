@extends('layouts.master')

@section('title')
    Daftar Toko
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Toko</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('toko.store') }}')" class="btn btn-success btn-s btn-flat"><i class="fa fa-plus-circle"></i> Tambah Toko</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('toko.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('toko.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_toko'},
                {data: 'alamat'},
                {data: 'nomor_telepon'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    
                        let isEdit = $('#modal-form [name=_method]').val() === 'put';
                    
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: isEdit ? 'Data toko berhasil diperbarui' : 'Data toko berhasil disimpan',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#4f9b8f',
                        });
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Toko');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_toko]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Toko');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_toko]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_toko]').val(response.nama_toko);
                $('#modal-form [name=alamat]').val(response.alamat);
                $('#modal-form [name=nomor_telepon]').val(response.nomor_telepon);
            })

            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

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
                            text: 'Toko berhasil dihapus',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#4f9b8f',
                            showConfirmButton: true,
                        });
                    })
                    .fail((errors) => {
                        // Show error message if deletion fails
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Tidak dapat menghapus data',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#d33',
                        });
                    });
            }
        });
    }

</script>
@endpush
