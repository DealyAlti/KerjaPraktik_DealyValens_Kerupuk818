<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\PermintaanBarang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF; // Ensure you import the PDF facade

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function cetak(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $jenisLaporan = $request->jenis_laporan;

        // Validasi: tanggal awal tidak boleh melebihi tanggal akhir
        if ($tanggalAwal > $tanggalAkhir) {
            return redirect()->route('laporan.index')->with('invalid_date', 'Tanggal awal tidak boleh melebihi akhir.');
        }

        // Ambil data sesuai jenis laporan
        if ($jenisLaporan == 'barang_keluar') {
            $laporan = BarangKeluar::leftJoin('produk', 'barang_keluar.id_produk', '=', 'produk.id_produk')
                ->leftJoin('pegawai', 'barang_keluar.id_pegawai', '=', 'pegawai.id_pegawai')
                ->select('barang_keluar.*', 'produk.nama_produk', 'pegawai.nama')
                ->whereBetween('barang_keluar.tanggal', [$tanggalAwal, $tanggalAkhir])
                ->get();
        } elseif ($jenisLaporan == 'barang_masuk') {
            $laporan = BarangMasuk::leftJoin('produk', 'barang_masuk.id_produk', '=', 'produk.id_produk')
                ->leftJoin('pegawai', 'barang_masuk.id_pegawai', '=', 'pegawai.id_pegawai')
                ->select('barang_masuk.*', 'produk.nama_produk', 'pegawai.nama')
                ->whereBetween('barang_masuk.tanggal', [$tanggalAwal, $tanggalAkhir])
                ->get();
        } else {
            $laporan = PermintaanBarang::join('produk', 'produk.id_produk', '=', 'permintaan_barang.id_produk')
                ->where('permintaan_barang.status', 'disetujui')
                ->whereBetween('permintaan_barang.tanggal_permintaan', [$tanggalAwal, $tanggalAkhir])
                ->select('permintaan_barang.*', 'produk.nama_produk')
                ->get();
        }

        // Jika data kosong → kembali ke halaman form dengan session
        if ($laporan->isEmpty()) {
            return redirect()->route('laporan.index')->with('no_data', 'Data tidak ditemukan untuk tanggal dan jenis laporan tersebut.');
        }

        // Jika ada data, buat PDF
        $pdf = PDF::loadView('laporan.cetak', compact('laporan', 'tanggalAwal', 'tanggalAkhir', 'jenisLaporan'));
        return $pdf->download('laporan_' . $jenisLaporan . '.pdf');
    }

}
