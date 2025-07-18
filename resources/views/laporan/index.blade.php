@extends('layouts.master')

@section('title')
    Cetak Laporan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Cetak Laporan</li>
@endsection

@section('content')
    <!-- Form untuk memilih laporan -->
    <form action="{{ route('laporan.cetak') }}" method="POST" target="_blank">
        @csrf
        <div class="row mb">
            <div class="col-sm-6">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" required
                    oninvalid="this.setCustomValidity('Tanggal awal harus diisi.')"
                    oninput="this.setCustomValidity('')">
            </div>
            <div class="col-sm-6">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required
                    oninvalid="this.setCustomValidity('Tanggal akhir harus diisi.')"
                    oninput="this.setCustomValidity('')">
            </div>
        </div>

        <div class="row mb">
            <div class="col-sm-12">
                <label for="jenis_laporan" class="form-label">Jenis Laporan</label>
                <select name="jenis_laporan" id="jenis_laporan" class="form-control" required
                    oninvalid="this.setCustomValidity('Silahkan pilih jenis laporan.')"
                    oninput="this.setCustomValidity('')">
                    <option value="">Pilih Laporan</option>
                    <option value="barang_masuk">Laporan Barang Masuk</option>
                    <option value="barang_keluar">Laporan Barang Keluar</option>
                    <option value="permintaan_disetujui">Permintaan Barang yang Disetujui</option>
                </select>
            </div>
        </div>
        <br>
        <div class="row mb-3">
            <div class="col-sm-3 align-self-end">
                <button type="submit" class="btn btn-primary w-100">Cetak</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    // Validasi sisi server (pesan dari session)
    @if(session('no_data'))
        Swal.fire({
            icon: 'warning',
            title: 'Tidak Ada Data',
            text: '{{ session('no_data') }}',
            confirmButtonText: 'OK'
        });
    @endif

    @if(session('invalid_date'))
        Swal.fire({
            icon: 'warning',
            title: 'Tanggal Tidak Valid',
            text: '{{ session('invalid_date') }}',
            confirmButtonText: 'OK'
        });
    @endif

    // Validasi sisi client (langsung saat submit form)
    document.querySelector('form').addEventListener('submit', function (e) {
        const tglAwal = document.getElementById('tanggal_awal').value;
        const tglAkhir = document.getElementById('tanggal_akhir').value;

        if (!tglAwal || !tglAkhir) return; // Biar HTML5 required jalan dulu

        if (tglAwal > tglAkhir) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Tanggal Tidak Valid',
                text: 'Tanggal awal tidak boleh melebihi tanggal akhir.',
                confirmButtonText: 'OK'
            });
        }
    });
</script>
@endpush

