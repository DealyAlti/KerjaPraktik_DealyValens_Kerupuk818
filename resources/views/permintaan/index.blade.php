@extends('layouts.master')

@section('title')
    Permintaan Barang
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Permintaan Barang</li>
@endsection

@section('content')
<form action="{{ route('permintaan.store') }}" method="POST" id="requestForm">
    @csrf
    <!-- Pemilihan Tanggal Permintaan -->
    <div class="form-group">
        <label for="tanggal_permintaan">Tanggal Permintaan</label>
        <input type="date" name="tanggal_permintaan" class="form-control" required
            oninvalid="this.setCustomValidity('Tanggal permintaan harus diisi.')"
            oninput="this.setCustomValidity('')">
    </div>

    <!-- Tombol Tambah Permintaan dengan margin bawah -->
    <button type="button" id="addRequest" class="btn btn-success mt-3">Tambah Permintaan</button>
    <br></br>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <table class="table mt-3" id="requestTable">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jumlah Permintaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data permintaan yang ditambahkan akan ditampilkan di sini -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tombol Buat Permintaan dengan margin atas -->
    <button type="submit" class="btn btn-primary mt-3" id="submitRequest">Buat Permintaan</button>
</form>

@endsection

@push('scripts')
<script>
    // Simpan data produk yang diminta di array
    let requests = [];

    // Menambah permintaan produk saat tombol "Tambah Permintaan" diklik
    document.getElementById('addRequest').addEventListener('click', function() {
        // Membuat form input produk dan jumlah
        const requestRow = document.createElement('tr');
        requestRow.innerHTML = `
            <td>
                <select name="id_produk[]" class="form-control" required
                        oninvalid="this.setCustomValidity('Silakan pilih produk.')"
                        oninput="this.setCustomValidity('')">
                    <option value="">Pilih Barang</option>
                    @foreach($produk as $item)
                        <option value="{{ $item->id_produk }}">
                            {{ $item->nama_produk }} ({{ $item->kategori->nama_kategori }}) : {{ $item->stok }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="jumlahPermintaan[]" class="form-control" required min="1"
                oninvalid="this.setCustomValidity(
                    this.validity.valueMissing ? 'Jumlah masuk harus diisi.' :
                    this.validity.rangeUnderflow ? 'Jumlah tidak boleh kurang dari 1.' : ''
                ); this.reportValidity();"
                oninput="this.setCustomValidity('')">
            </td>
            <td><button type="button" class="btn btn-danger btn-sm removeRequest">Hapus</button></td>
        `;
        
        // Menambahkan row ke tabel
        document.querySelector('#requestTable tbody').appendChild(requestRow);

        // Menambahkan event listener untuk menghapus row
        requestRow.querySelector('.removeRequest').addEventListener('click', function() {
            requestRow.remove();
        });
    });

    // Handle form submission and show SweetAlert2
    document.getElementById('requestForm').addEventListener('submit', function(e) {
        e.preventDefault();  // Prevent the default form submission

        // Use AJAX to send the data to the server
        $.ajax({
            url: "{{ route('permintaan.store') }}",  // URL untuk menyimpan data
            method: "POST",
            data: $(this).serialize(),  // Kirimkan semua data formulir
            success: function(response) {
                // Menampilkan pesan sukses
                Swal.fire({
                    title: 'Permintaan Barang Berhasil Dibuat!',
                    icon: 'success',
                    confirmButtonText: 'OK',
                }).then(() => {
                    // Menghapus semua baris produk yang ada di tabel
                    document.querySelector('#requestTable tbody').innerHTML = '';

                    // Reset semua inputan dalam form
                    document.getElementById('requestForm').reset();
                });
            },
            error: function(xhr, status, error) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Terjadi kesalahan saat menyimpan data.';

                // Menampilkan pesan error menggunakan SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            }
        });
    });
</script>
@endpush
