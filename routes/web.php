<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    ProdukController,
    UserController,
    PegawaiController,
    BarangMasukController,
    BarangKeluarController,
    LaporanController,
    PermintaanBarangController,
    TokoController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/redirect-after-login', function () {
    $user = Auth::user();

    if ($user->level == 0) {
        return redirect()->route('dashboard');
    } elseif ($user->level == 1) {
        return redirect()->route('kepala.dashboard'); // Bisa diganti jika kamu punya dashboard kepala gudang
    } elseif ($user->level == 2) {
        return redirect()->route('kasir.dashboard');
    }

    return redirect('/');
})->middleware('auth');

Route::group(['middleware' => 'auth'], function () {


    Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('/user', UserController::class);

    Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::resource('/produk', ProdukController::class);

    Route::prefix('permintaan')->name('permintaan.')->group(function () {
        Route::post('/simpanpermintaan', [PermintaanBarangController::class, 'store'])->name('store'); // Simpan permintaan
        Route::get('/data', [PermintaanBarangController::class, 'data'])->name('data');  // Ambil data permintaan barang
        Route::get('list', [PermintaanBarangController::class, 'list'])->name('list');
        Route::post('update/{id}', [PermintaanBarangController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('{id}', [PermintaanBarangController::class, 'destroy'])->name('destroy');
        Route::post('batal/{id}', [PermintaanBarangController::class, 'cancelRequest'])->name('cancelRequest');
        Route::get('/get-produk-by-kategori/{id}', [PermintaanBarangController::class, 'getProdukByKategori'])->name('getproduk');


    });
    

    Route::group(['middleware' => 'level:0,1'], function () {
        Route::get('/barangmasuk/data', [BarangMasukController::class, 'data'])->name('barangmasuk.data'); // Custom route for DataTables
        Route::resource('/barangmasuk', BarangMasukController::class); // Resource route (Handles CRUD operations)
        Route::delete('/barangmasuk/{id}', [BarangMasukController::class, 'destroy'])->name('barang_masuk.destroy'); // Custom delete route
        Route::get('/get-produk-by-kategori/{id}', [BarangMasukController::class, 'getProdukByKategori'])->name('get.produk.by.kategori');
        
        Route::get('barangkeluar/data', [BarangKeluarController::class, 'data'])->name('barangkeluar.data');
        Route::resource('/barangkeluar', BarangKeluarController::class);
        Route::delete('/barangkeluar/{id}', [BarangKeluarController::class, 'destroy'])->name('barang_keluar.destroy');
        Route::get('/get-produk-by-kategori/{id}', [BarangKeluarController::class, 'getProdukByKategori'])->name('barangkeluar.getproduk');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    });
    Route::group(['middleware' => 'level:0'], function () {
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);

        Route::get('/pegawai/data', [PegawaiController::class, 'data'])->name('pegawai.data');
        Route::resource('/pegawai', PegawaiController::class);

        Route::get('/toko/data', [TokoController::class, 'data'])->name('toko.data');
        Route::resource('/toko', TokoController::class);

        Route::get('/dashboard/permintaan-per-toko-per-tanggal', [DashboardController::class, 'permintaanPerTokoPerTanggal'])->name('dashboard.permintaan-per-toko-per-tanggal');
        Route::get('/dashboard/barang-masuk-keluar-per-produk', [DashboardController::class, 'barangMasukKeluarPerProduk'])->name('dashboard.barang-masuk-keluar-per-produk');
    });

    Route::group(['middleware' => 'level:2'], function () {
        Route::get('/kasir/dashboard', [DashboardController::class, 'kasirDashboard'])->name('kasir.dashboard');
        Route::get('/formpermintaan', [PermintaanBarangController::class, 'index'])->name('permintaan.index'); // Form permintaan
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/kepala/dashboard', [DashboardController::class, 'kepalaDashboard'])->name('kepala.dashboard');
    });
    
    Route::group(['middleware' => 'level:0'], function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});
