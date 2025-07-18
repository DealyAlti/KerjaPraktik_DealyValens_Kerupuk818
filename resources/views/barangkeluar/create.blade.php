@extends('layouts.master')

@section('title')
    Tambah Barang Keluar
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Tambah Barang Keluar</li>
@endsection

@section('content')
<form action="{{ route('barangkeluar.store') }}" method="POST" id="requestForm">
    @csrf
    <!-- Pemilihan Tanggal Keluar -->
    <div class="form-group">
        <label for="tanggal_keluar">Tanggal Keluar</label>
        <input type="date" name="tanggal_keluar" class="form-control" required
            oninvalid="this.setCustomValidity('Tanggal keluar harus diisi.')"
            oninput="this.setCustomValidity('')">
    </div>
    
    <div class="form group">
        <label for="id_pegawai">Penanggung Jawab</label>
        <select name="id_pegawai[]" class="form-control" required
            oninvalid="this.setCustomValidity('Silakan pilih pegawai.')"
            oninput="this.setCustomValidity('')">
            <option value="">Pilih Pegawai</option>
            @foreach($pegawai as $item)
                <option value="{{ $item->id_pegawai }}">{{ $item->nama }}</option>
            @endforeach
        </select>
        <span class="help-block with-errors"></span>
    </div>
    <div class="form-group">
        <label for="keterangan">Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control" required autofocus
                oninvalid="this.setCustomValidity('Keterangan harus diisi.')"
                oninput="this.setCustomValidity('')">
            <span class="help-block with-errors"></span>
    </div>

    <!-- Tombol Tambah Keluar dengan margin bawah -->
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
                            <th>Jumlah Keluar</th>
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
                <select class="form-control kategori-select" required>
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
                <input type="number" name="jumlahKeluar[]" class="form-control" required min="1"
                    oninvalid="this.setCustomValidity('Jumlah tidak boleh kosong atau kurang dari 1.')"
                    oninput="this.setCustomValidity('')">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm removeRequest">Hapus</button>
            </td>
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
            url: "{{ route('barangkeluar.store') }}",  // URL untuk menyimpan data
            method: "POST",
            data: $(this).serialize(),  // Kirimkan semua data formulir
            success: function(response) {
                // Menampilkan pesan sukses
                Swal.fire({
                    title: 'Barang Keluar Berhasil Dibuat!',
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
            error: function(xhr) {
                if (xhr.status === 400 && xhr.responseJSON?.errors) {
                    let messages = xhr.responseJSON.errors.join('<br>');
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Tidak Cukup',
                        html: messages,
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Silakan coba lagi.',
                    });
                }
            }
        });
    });
    
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('kategori-select')) {
            const kategoriSelect = e.target;
            const row = kategoriSelect.closest('tr');
            const produkSelect = row.querySelector('.produk-select');
            const kategoriId = kategoriSelect.value;
    
            produkSelect.innerHTML = '<option value="">Loading...</option>';
            produkSelect.disabled = true;
    
            fetch(`/get-produk-by-kategori/${kategoriId}`)
                .then(res => res.json())
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
