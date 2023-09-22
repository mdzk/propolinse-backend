<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'pembayaran';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'co_id', 'nm_pengirim', 'no_rek', 'jmlh_transfer', 'bank', 'image'];

    /*public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }*/
    public function children()
    {
        return $this->hasMany(Konfirmasi::class);
    }
}
