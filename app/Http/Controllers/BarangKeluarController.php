<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Produk;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produk = Produk::all()->pluck('nama_produk', 'id_produk');
        $pegawai = Pegawai::all()->pluck('nama', 'id_pegawai');
        
        
        return view('barangkeluar.index', compact('produk','pegawai'));

    }

    public function data(Request $request)
    {
        $barang_keluar = BarangKeluar::leftJoin('produk', 'produk.id_produk','=', 'barang_keluar.id_produk')
            ->leftjoin('pegawai', 'barang_keluar.id_pegawai', '=', 'pegawai.id_pegawai')
            ->leftJoin('kategori', 'produk.id_kategori', '=', 'kategori.id_kategori')
            ->select('barang_keluar.*', 'produk.nama_produk', 'pegawai.nama', 'kategori.nama_kategori');
    
        // Memeriksa apakah ada parameter start_date dan end_date
        if ($request->has('start_date') && $request->has('end_date')) {
            // Validasi dan filter berdasarkan rentang tanggal
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        
            // Memastikan tanggal yang diterima memiliki format yang sesuai
            if ($start_date && $end_date) {
                // Filter data berdasarkan rentang tanggal
                $barang_keluar = $barang_keluar->whereBetween('barang_keluar.tanggal', [$start_date, $end_date]);
            }
        }
    
        // Menampilkan data setelah filter
        $barang_keluar = $barang_keluar->get();
    
        return datatables()
            ->of($barang_keluar)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($barang_keluar) {
                return tanggal_indonesia($barang_keluar->tanggal, false);
            })
            ->addColumn('aksi', function ($barang_keluar) {
                $actions = '';
                if (auth()->user()->level == 0) {
                    $actions = '
                    <div class="btn-group">
                        <button type="button" onclick="deleteData(`'. route('barang_keluar.destroy', $barang_keluar->id_barangkeluar) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    </div>';
                }
                return $actions;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk = Produk::all(); // Ambil semua produk untuk dropdown
        $pegawai = Pegawai::all(); // Ambil semua pegawai untuk dropdown
        return view('barangkeluar.create', compact('produk', 'pegawai')); // Pastikan view ini ada
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi awal
        $validated = $request->validate([
            'tanggal_keluar' => 'required|date',
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'id_produk' => 'required|array',
            'id_produk.*' => 'exists:produk,id_produk',
            'jumlahKeluar' => 'required|array',
            'jumlahKeluar.*' => 'integer|min:1',
        ]);

        $tanggalKeluar = $request->tanggal_keluar;
        $idPegawai = $request->id_pegawai[0]; // Ambil pegawai pertama
        $errorProduk = [];

        // ✨ 1. Simulasikan dulu: cek stok semua produk
        foreach ($request->id_produk as $key => $produkId) {
            $jumlahKeluar = $request->jumlahKeluar[$key];
            $produk = Produk::find($produkId);

            if (!$produk) {
                $errorProduk[] = "Produk dengan ID $produkId tidak ditemukan.";
            } elseif ($produk->stok < $jumlahKeluar) {
                $errorProduk[] = "Stok ({$produk->nama_produk}) tidak mencukupi.";
            }
        }

        // Jika ada error, kembalikan error tanpa mengurangi stok apapun
        if (!empty($errorProduk)) {
            return response()->json(['errors' => $errorProduk], 400);
        }

        // ✨ 2. Semua valid, sekarang kurangi stok & simpan
        foreach ($request->id_produk as $key => $produkId) {
            $jumlahKeluar = $request->jumlahKeluar[$key];
            $produk = Produk::find($produkId);

            $produk->stok -= $jumlahKeluar;
            $produk->save();

            BarangKeluar::create([
                'tanggal' => $tanggalKeluar,
                'id_pegawai' => $idPegawai,
                'id_produk' => $produkId,
                'jumlahKeluar' => $jumlahKeluar,
                'keterangan' => $request->keterangan,
            ]);
        }

        return response()->json(['success' => 'Barang keluar berhasil ditambahkan!'], 200);
    }

    
    



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $barang_keluar = BarangKeluar::find($id);
        return response()->json($barang_keluar);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BarangKeluar  $barangKeluar
     * @return \Illuminate\Http\Response
     */
    public function edit(BarangKeluar $barangKeluar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BarangKeluar  $barangKeluar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BarangKeluar $barangKeluar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BarangKeluar  $barangKeluar
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Cari data barang keluar berdasarkan ID
        $barang_keluar = BarangKeluar::find($id);
    
        if ($barang_keluar) {
            // Ambil produk terkait dari barang keluar
            $produk = Produk::find($barang_keluar->id_produk);
    
            if ($produk) {
                // Tambahkan stok produk dengan jumlah barang yang keluar (karena barang keluar dihapus)
                $produk->stok += $barang_keluar->jumlahKeluar;
                $produk->save();  // Simpan perubahan stok ke database
            }
    
            // Hapus data barang keluar
            $barang_keluar->delete();
    
            // Kembalikan response sukses
            return response(null, 204);
        }
    
        // Jika barang keluar tidak ditemukan
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }
    
}
