<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    protected $guarded = [];
    protected $fillable = [
        'nama', 'alamat', 'telepon', 'jabatan'
    ];

    public function barangKeluar() {
        return $this->hasMany(BarangKeluar::class, 'id_pegawai');
    }
    public function barangMasuk() {
        return $this->hasMany(BarangMasuk::class, 'id_pegawai');
    }

}
