<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanBarang extends Model
{
    use HasFactory;
    protected $table = 'permintaan_barang';
    protected $primaryKey = 'id_permintaanbarang';
    protected $guarded = [];
    protected $fillable = [
        'id_produk', 'id_user', 'tanggal_permintaan', 'jumlahPermintaan', 'status', 'alasan_penolakan'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id'); // Menentukan relasi dengan kolom id_user
    }


}
