<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Produk;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $produk = Produk::all()->pluck('nama_produk', 'id_produk');
        $pegawai = Pegawai::all()->pluck('nama', 'id_pegawai');
        
        return view('barangmasuk.index', compact('produk', 'pegawai'));

    }

    public function data(Request $request)
    {
        // Query dasar dengan join
        $barang_masuk = BarangMasuk::leftJoin('produk', 'barang_masuk.id_produk', '=', 'produk.id_produk')
            ->leftJoin('pegawai', 'barang_masuk.id_pegawai', '=', 'pegawai.id_pegawai')
            ->leftJoin('kategori', 'produk.id_kategori', '=', 'kategori.id_kategori') // Join tabel kategori
            ->select('barang_masuk.*', 'produk.nama_produk', 'pegawai.nama', 'kategori.nama_kategori'); // Tambahkan kategori.nama_kategori
    
        // Memeriksa apakah ada parameter start_date dan end_date
        if ($request->has('start_date') && $request->has('end_date')) {
            // Validasi dan filter berdasarkan rentang tanggal
            $start_date = $request->start_date;
            $end_date = $request->end_date;
    
            // Memastikan tanggal yang diterima memiliki format yang sesuai
            if ($start_date && $end_date) {
                // Filter data berdasarkan rentang tanggal
                $barang_masuk = $barang_masuk->whereBetween('barang_masuk.tanggal', [$start_date, $end_date]);
            }
        }
    
        // Menampilkan data setelah filter
        $barang_masuk = $barang_masuk->get();
    
        // Mengembalikan data ke DataTables
        return datatables()
            ->of($barang_masuk)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($barang_masuk) {
                // Mengubah format tanggal jika diperlukan
                return tanggal_indonesia($barang_masuk->tanggal, false);
            })
            ->addColumn('aksi', function ($barang_masuk) {
                $actions = '';
                if (auth()->user()->level == 0) {
                    $actions = '
                    <div class="btn-group">
                        <button type="button" onclick="deleteData(`'. route('barang_masuk.destroy', $barang_masuk->id_barangmasuk) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        return view('barangmasuk.create', compact('produk', 'pegawai')); // Pastikan view ini ada
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'tanggal_masuk' => 'required|date',
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'id_produk' => 'required|array',
            'id_produk.*' => 'exists:produk,id_produk',
            'jumlahMasuk' => 'required|array',
            'jumlahMasuk.*' => 'integer|min:1',
        ]);
    
        // Ambil tanggal dan id_pegawai dari request
        $tanggalMasuk = $request->tanggal_masuk;
        $idPegawai = $request->id_pegawai[0]; // Ambil id pegawai pertama (bisa disesuaikan jika multiple pegawai)
    
        // Iterasi produk yang dimasukkan
        foreach ($request->id_produk as $key => $produkId) {
            $jumlahMasuk = $request->jumlahMasuk[$key];
    
            // Cari produk berdasarkan id_produk
            $produk = Produk::find($produkId);
    
            // Pastikan produk ditemukan
            if ($produk) {
                // Tambahkan stok produk dengan jumlah barang yang masuk
                $produk->stok += $jumlahMasuk;
                $produk->save();
    
                // Simpan data barang masuk
                BarangMasuk::create([
                    'tanggal' => $tanggalMasuk,
                    'id_pegawai' => $idPegawai,
                    'id_produk' => $produkId,
                    'jumlahMasuk' => $jumlahMasuk,
                ]);
            } else {
                return response()->json(['error' => 'Produk dengan ID ' . $produkId . ' tidak ditemukan.'], 404);
            }
        }
        return response()->json(['success' => 'Barang masuk berhasil ditambahkan!'], 200);
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $barang_masuk = BarangMasuk::find($id);
        return response()->json($barang_masuk);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $produk = Produk::findOrFail($barangMasuk->id_produk);

        // Cek apakah stok saat ini cukup untuk dikurangi
        if ($produk->stok < $barangMasuk->jumlahMasuk) {
            return response()->json([
                'message' => 'Data tidak dapat dihapus karena stok sudah digunakan.'
            ], 422);
        }

        // Kurangi stok sesuai jumlah barang masuk yang akan dihapus
        $produk->stok -= $barangMasuk->jumlahMasuk;
        $produk->save();

        $barangMasuk->delete();

        return response()->json(['message' => 'Barang masuk berhasil dihapus.']);
    }
    
    public function getProdukByKategori($id)
    {
        $produk = Produk::where('id_kategori', $id)->get();
    
        return response()->json($produk);
    }



    
}
