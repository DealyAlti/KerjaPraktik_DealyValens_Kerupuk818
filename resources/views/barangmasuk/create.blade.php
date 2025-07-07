@extends('layouts.master')

@section('title')
    Tambah Barang Masuk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Tambah Barang Masuk</li>
@endsection

@section('content')
<form action="{{ route('barangmasuk.store') }}" method="POST" id="requestForm">
    @csrf
    <!-- Pemilihan Tanggal Masuk -->
    <div class="form-group">
        <label for="tanggal_masuk">Tanggal Masuk</label>
        <input type="date" name="tanggal_masuk" class="form-control" required
            oninvalid="this.setCustomValidity('Tanggal masuk harus diisi.')"
            oninput="this.setCustomValidity('')">
    </div>
    
    <div class="form group">
        <label for="id_pegawai">Penanggung Jawab</label>
        <select name="id_pegawai[]" class="form-control" required
            oninvalid="this.setCustomValidity('Silakan pilih pegawai.')"
            oninput="this.setCustomValidity('')">
            <option value="" disabled selected>Pilih Pegawai</option>
            @foreach($pegawai as $item)
                <option value="{{ $item->id_pegawai }}">{{ $item->nama }}</option>
            @endforeach
        </select>

        <span class="help-block with-errors"></span>
    </div>

    <!-- Tombol Tambah Masuk dengan margin bawah -->
    <button type="button" id="addRequest" class="btn btn-success mt-3">Tambah Produk</button>
    <br></br>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <table class="table mt-3" id="requestTable">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jumlah Masuk</th>
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
    <button type="submit" class="btn btn-primary mt-3" id="submitRequest">Simpan</button>
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
                    <option value="">Pilih Produk</option>
                    @foreach($produk as $item)
                        <option value="{{ $item->id_produk }}">
                            {{ $item->nama_produk }} ({{ $item->kategori->nama_kategori }}) : {{ $item->stok }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="jumlahMasuk[]" class="form-control" required min="1"
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

    document.getElementById('requestForm').addEventListener('submit', function(e) {
        e.preventDefault();  // Prevent the default form submission

        // Use AJAX to send the data to the server
        $.ajax({
            url: "{{ route('barangmasuk.store') }}",  // URL untuk menyimpan data
            method: "POST",
            data: $(this).serialize(),  // Kirimkan semua data formulir
            success: function(response) {
                // Menampilkan pesan sukses
                Swal.fire({
                    title: 'Barang Masuk Berhasil Dibuat!',
                    icon: 'success',
                    confirmButtonText: 'OK',
                }).then(() => {
                    // Menghapus semua baris produk yang ada di tabel
                    document.querySelector('#requestTable tbody').innerHTML = '';

                    // Reset semua inputan dalam form
                    document.getElementById('requestForm').reset();

                    // Jika menggunakan DataTable, bisa lakukan reload
                    // table.ajax.reload(); // Jika Anda menggunakan DataTable untuk menampilkan data
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            }
        });
    });


</script>
@endpush
