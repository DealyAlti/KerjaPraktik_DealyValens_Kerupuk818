<?php

namespace App\Http\Controllers;

use App\Models\PermintaanBarang;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\Kategori;

class PermintaanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produk = Produk::all();
        $kategori = Kategori::all(); // <- Tambahkan ini
    
        return view('permintaan.index', compact('produk', 'kategori')); // <- Tambahkan 'kategori' di compact
    }

    public function data()
    {
        $user = auth()->user();

        if ($user->level == 0 || $user->level == 1) {
            $permintaan = PermintaanBarang::with('produk.kategori', 'user')
                ->orderByRaw("FIELD(status, 'pending') DESC") // status 'pending' paling atas
                ->orderBy('tanggal_permintaan', 'asc')        // urutkan berdasarkan tanggal terlama
                ->get();
        } else {
            $permintaan = PermintaanBarang::with('produk.kategori', 'user')
                ->where('id_user', $user->id)
                ->orderByRaw("FIELD(status, 'pending') DESC")
                ->orderBy('tanggal_permintaan', 'asc')
                ->get();
        }

        return datatables()
            ->of($permintaan)
            ->addIndexColumn()
            ->addColumn('tanggal_permintaan', function ($permintaan_barang) {
                return tanggal_indonesia($permintaan_barang->tanggal_permintaan, false);
            })
            ->addColumn('kategori', function ($permintaan_barang) {
                return $permintaan_barang->produk->kategori->nama_kategori ?? 'Tidak Ada Kategori';
            })
            ->addColumn('user', function ($permintaan_barang) {
                $nama = $permintaan_barang->user->name ?? '-';
                $toko = $permintaan_barang->user->toko->nama_toko ?? '-';
                return "$nama ($toko)";
            })
            ->make(true);
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_permintaan' => 'required|date',
            'id_produk' => 'required|array', // Menjamin ada beberapa produk yang dipilih
            'id_produk.*' => 'exists:produk,id_produk', // Memastikan produk_id valid
            'jumlahPermintaan' => 'required|array',
            'jumlahPermintaan.*' => 'required|integer|min:1',
        ]);
        // Simpan permintaan barang
        foreach ($request->id_produk as $key => $produkId) {
            $produk = Produk::find($produkId);
            // Periksa apakah jumlah permintaan melebihi stok produk
            if ($produk->stok < $request->jumlahPermintaan[$key]) {
                return response()->json(['error' => 'Stok Produk Tidak Mencukupi'], 400);
            }
            // Simpan permintaan barang jika stok cukup
            PermintaanBarang::create([
                'id_produk' => $produkId,
                'id_user' => auth()->user()->id,  // Menyimpan id_user dari user yang login
                'jumlahPermintaan' => $request->jumlahPermintaan[$key],
                'tanggal_permintaan' => $request->tanggal_permintaan,
            ]);
        }
    }


    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $permintaan = PermintaanBarang::with('produk', 'user')->get(); // Menambahkan relasi produk dan user
        return view('permintaan.list', compact('permintaan'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $permintaan = PermintaanBarang::find($id);

        if ($permintaan) {
            $status = $request->status;

            // Jika status adalah "ditolak", simpan alasan penolakan
            if ($status == 'ditolak' && empty($request->alasan_penolakan)) {
                return redirect()->route('permintaan.list')->with('error', 'Alasan penolakan harus diisi.');
            }

            if (!in_array($status, ['disetujui', 'ditolak'])) {
                return redirect()->route('permintaan.list')->with('error', 'Status tidak valid');
            }

            // Jika status disetujui
            if ($status == 'disetujui') {
                $produk = Produk::find($permintaan->id_produk);

                if ($produk->stok < $permintaan->jumlahPermintaan) {
                    return redirect()->route('permintaan.list')->with('error', 'Stok tidak mencukupi untuk memenuhi permintaan.');
                }

                $produk->stok -= $permintaan->jumlahPermintaan;
                $produk->save();
            }

            // Simpan status dan alasan penolakan jika ada
            $permintaan->status = $status;
            $permintaan->alasan_penolakan = $request->alasan_penolakan ?? null; // Simpan alasan penolakan jika ada
            $permintaan->save();

            // Menampilkan pesan sukses jika status disetujui
            if ($status == 'disetujui') {
                return redirect()->route('permintaan.list')->with('success', 'Permintaan Barang ' . ucfirst($status));
            }

            // Menampilkan pesan error jika status ditolak
            return redirect()->route('permintaan.list')->with('error', 'Permintaan Barang ' . ucfirst($status));
        }

        return redirect()->route('permintaan.list')->with('error', 'Permintaan tidak ditemukan');
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cancelRequest($id)
    {
        // Ambil permintaan berdasarkan ID
        $permintaan = PermintaanBarang::find($id);
        
        if ($permintaan) {
            // Ubah status menjadi batal
            $permintaan->status = 'batal';
            $permintaan->save();
            
            return response()->json(['message' => 'Permintaan berhasil dibatalkan']);
        }

        return response()->json(['message' => 'Permintaan tidak ditemukan'], 404);
    }
    
    public function getProdukByKategori($id)
    {
        $produk = Produk::where('id_kategori', $id)->get();
        return response()->json($produk);
    }


}
