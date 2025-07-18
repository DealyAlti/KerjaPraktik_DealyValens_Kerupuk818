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
                            <th>Kategori</th>
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
            <tr>
                <td>
                    <select name="kategori_id[]" class="form-control kategori-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach(\App\Models\Kategori::all() as $kategori)
                            <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="id_produk[]" class="form-control produk-select" required disabled>
                        <option value="">Pilih Produk</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="jumlahMasuk[]" class="form-control" required min="1">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm removeRequest">Hapus</button>
                </td>
            </tr>
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
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('kategori-select')) {
            const kategoriSelect = e.target;
            const row = kategoriSelect.closest('tr');
            const produkSelect = row.querySelector('.produk-select');
    
            const kategoriId = kategoriSelect.value;
    
            produkSelect.innerHTML = '<option value="">Loading...</option>';
            produkSelect.disabled = true;
    
            fetch(`/get-produk-by-kategori/${kategoriId}`)
                .then(response => response.json())
                .then(data => {
                    produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
                    data.forEach(produk => {
                        produkSelect.innerHTML += `<option value="${produk.id_produk}">${produk.nama_produk} (Stok: ${produk.stok})</option>`;
                    });
                    produkSelect.disabled = false;
                });
        }
    });



</script>
@endpush
