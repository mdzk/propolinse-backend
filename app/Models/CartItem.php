<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //protected $fillable = ['barang_id', 'quantity', 'total_harga'];
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Barang::class, 'barang_id'); // Sesuaikan dengan model produk Anda
    }
}
