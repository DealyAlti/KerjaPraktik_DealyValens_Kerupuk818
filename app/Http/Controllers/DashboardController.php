<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Pegawai;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\PermintaanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        $kategori = Kategori::count();
        $produk = Produk::count();
        $pegawai = Pegawai::count();
        $barang_masuk = BarangMasuk::count();
        $barang_keluar = BarangKeluar::count();

        // Ambil produk yang stoknya kurang dari 100
        $produk_menipis = Produk::where('stok', '<', 100)->get();
        // Fetch pending product requests
        $pendingRequests = PermintaanBarang::where('status', 'pending')->count();

        return view('admin.dashboard', compact('kategori', 'produk', 'pegawai','barang_masuk','barang_keluar', 'produk_menipis', 'pendingRequests'));
      
    }

    public function kasirDashboard()
    {
        $user = auth()->user();
        $toko = $user->toko; // pastikan relasi user → toko sudah ada (hasOne atau belongsTo)

        return view('admin.kasir', compact('user', 'toko'));
    }

    public function kepalaDashboard()
    {
        $user = auth()->user();
        $barang_masuk = BarangMasuk::count();
        $barang_keluar = BarangKeluar::count();
        $total_permintaan = PermintaanBarang::count();
        $produk_menipis = Produk::where('stok', '<', 100)->get();
        $pendingRequests = PermintaanBarang::where('status', 'pending')->count();

        return view('admin.kepala', compact('barang_masuk', 'barang_keluar', 'total_permintaan', 'produk_menipis', 'pendingRequests','user'));
    }


    public function permintaanPerTokoPerTanggal(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');

        // Ambil data permintaan per tanggal dan per toko
        $data = DB::table('permintaan_barang as p')
            ->join('users as u', 'p.id_user', '=', 'u.id')
            ->join('toko as t', 'u.id_toko', '=', 't.id_toko')
            ->select('t.nama_toko', 'p.tanggal_permintaan', DB::raw('SUM(p.jumlahPermintaan) as total_permintaan'))
            ->whereBetween('p.tanggal_permintaan', [$startDate, $endDate])
            ->groupBy('t.nama_toko', 'p.tanggal_permintaan')
            ->orderBy('p.tanggal_permintaan')
            ->get();

        // Ambil semua tanggal dalam rentang sebagai label grafik
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );

        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        // Ambil nama toko unik
        $tokoNames = $data->pluck('nama_toko')->unique();

        // Struktur data untuk Chart.js
        $datasets = [];

        foreach ($tokoNames as $toko) {
            $dataToko = $data->where('nama_toko', $toko);

            // Buat array jumlah permintaan per tanggal, default 0 jika tidak ada
            $jumlahPerTanggal = [];
            foreach ($dates as $d) {
                $record = $dataToko->firstWhere('tanggal_permintaan', $d);
                $jumlahPerTanggal[] = $record ? (int)$record->total_permintaan : 0;
            }

            $datasets[] = [
                'label' => $toko,
                'data' => $jumlahPerTanggal,
                'fill' => false,
                'borderColor' => '#' . substr(md5($toko), 0, 6), // warna random dari nama toko
                'tension' => 0.1
            ];
        }

        return response()->json([
            'labels' => $dates,
            'datasets' => $datasets,
        ]);
    }
    public function barangMasukKeluarPerProduk(Request $request)
    {
        // Ambil filter tanggal dari form
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');

        // Ambil data barang masuk
        $barangMasuk = DB::table('barang_masuk as bm')
            ->join('produk as p', 'bm.id_produk', '=', 'p.id_produk')
            ->select('p.nama_produk', 'bm.tanggal', DB::raw('SUM(bm.jumlahMasuk) as total_masuk'))
            ->whereBetween('bm.tanggal', [$startDate, $endDate])  // Pastikan filter tanggal bekerja
            ->groupBy('p.nama_produk', 'bm.tanggal')
            ->orderBy('bm.tanggal')
            ->get();

        // Ambil data barang keluar
        $barangKeluar = DB::table('barang_keluar as bk')
            ->join('produk as p', 'bk.id_produk', '=', 'p.id_produk')
            ->select('p.nama_produk', 'bk.tanggal', DB::raw('SUM(bk.jumlahKeluar) as total_keluar'))
            ->whereBetween('bk.tanggal', [$startDate, $endDate])  // Pastikan filter tanggal bekerja
            ->groupBy('p.nama_produk', 'bk.tanggal')
            ->orderBy('bk.tanggal')
            ->get();

        // Ambil semua tanggal dalam rentang sebagai label grafik
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );

        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        // Menggabungkan data barang masuk dan keluar
        $produkNames = $barangMasuk->pluck('nama_produk')
            ->merge($barangKeluar->pluck('nama_produk'))
            ->unique();
        $datasetsMasuk = [];
        $datasetsKeluar = [];

        foreach ($produkNames as $produk) {
            // Data barang masuk per produk
            $dataMasuk = $barangMasuk->where('nama_produk', $produk);
            $jumlahMasuk = [];
            foreach ($dates as $d) {
                $record = $dataMasuk->firstWhere('tanggal', $d);
                $jumlahMasuk[] = $record ? (int)$record->total_masuk : 0;
            }

            // Data barang keluar per produk
            $dataKeluar = $barangKeluar->where('nama_produk', $produk);
            $jumlahKeluar = [];
            foreach ($dates as $d) {
                $record = $dataKeluar->firstWhere('tanggal', $d);
                $jumlahKeluar[] = $record ? (int)$record->total_keluar : 0;
            }

            // Dataset untuk barang masuk dan keluar
            $datasetsMasuk[] = [
                'label' => $produk . ' (Masuk)',
                'data' => $jumlahMasuk,
                'fill' => false,
                'borderColor' => '#' . substr(md5($produk), 0, 6),
                'tension' => 0.1
            ];

            $datasetsKeluar[] = [
                'label' => $produk . ' (Keluar)',
                'data' => $jumlahKeluar,
                'fill' => false,
                'borderColor' => '#' . substr(md5($produk), 0, 6),
                'tension' => 0.1
            ];
        }

        return response()->json([
            'labels' => $dates,
            'datasetsMasuk' => $datasetsMasuk,
            'datasetsKeluar' => $datasetsKeluar,
        ]);
    }


}
