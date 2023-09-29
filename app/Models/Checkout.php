<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;
    protected $table = 'checkouts';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'status', 'newcart_id', 'nama', 'alamat', 'kode_pos', 'pengiriman', 'ongkir', 'bank', 'image', 'total_bayar'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
    public function bayar()
    {
        return $this->hasOne(Pembayaran::class, 'pembayaran_id', 'id');
    }

    public function cart()
    {
        return $this->belongsTo(Pembayaran::class, 'newcart_id', 'id');
    }
}
