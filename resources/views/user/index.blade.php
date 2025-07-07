@extends('layouts.master')

@section('title')
    Daftar Pengguna
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pengguna</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('user.store') }}')" class="btn btn-success btn-s btn-flat">
                    <i class="fa fa-plus-circle"></i> Tambah Pengguna
                </button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered" id="users-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Hak Akses</th>
                            <th>Toko</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('user.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('#users-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('user.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'name'},
                {data: 'email'},
                {data: 'level'},
                {data: 'toko'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data pengguna berhasil disimpan.',
                        });
                    })
                    .fail((errors) => {
                        if (errors.status === 422) {
                            let response = errors.responseJSON.errors;
                            let errorMessage = '';

                            if (response.name) {
                                errorMessage = response.name[0];
                            } else if (response.email) {
                                errorMessage = response.email[0];
                            } else if (response.password) {
                                errorMessage = response.password[0];
                            } else {
                                errorMessage = 'Data tidak valid. Silakan periksa kembali form.';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menyimpan',
                                text: errorMessage,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Tidak dapat menyimpan data.',
                            });
                        }
                    });
            }
        });

        $('#modal-form').on('change', 'select[name=level]', function() {
            toggleTokoSelect();
        });

        $('#modal-form').on('shown.bs.modal', function () {
            toggleTokoSelect();
            
        });

        function toggleTokoSelect() {
            const level = $('#modal-form select[name=level]').val();
            if(level == '2') {
                $('#div-toko').show();
                $('#modal-form select[name=id_toko]').attr('required', true);
            } else {
                $('#div-toko').hide();
                $('#modal-form select[name=id_toko]').removeAttr('required').val('');
            }
        }
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Pengguna');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();
        $('#modal-form select[name=level]').val('');
        $('#div-toko').hide();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Pengguna');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=name]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=name]').val(response.name);
                $('#modal-form [name=email]').val(response.email);
                $('#modal-form [name=password]').val('');
                $('#modal-form [name=password_confirmation]').val('');
                $('#modal-form select[name=level]').val(response.level).trigger('change');

                if (response.level == 2) {
                    $('#modal-form select[name=id_toko]').val(response.id_toko);
                    $('#div-toko').show();
                } else {
                    $('#modal-form select[name=id_toko]').val('');
                    $('#div-toko').hide();
                }
            });
    }

    function deleteData(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan bisa mengembalikan data yang dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done(() => {
                    table.ajax.reload();
                    Swal.fire(
                        'Berhasil',
                        'Pengguna berhasil dihapus',
                        'success'
                    );
                })
                .fail(() => {
                    Swal.fire(
                        'Gagal',
                        'Tidak dapat menghapus data',
                        'error'
                    );
                });
            }
        });
    }
</script>
@endpush
