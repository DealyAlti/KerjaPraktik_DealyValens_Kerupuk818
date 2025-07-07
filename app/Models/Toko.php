<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;
    protected $table = 'toko';
    protected $primaryKey = 'id_toko';
    protected $fillable = ['nama_toko', 'alamat', 'nomor_telepon'];

    public function users() {
        return $this->hasMany(User::class, 'id_toko');
    }

}
