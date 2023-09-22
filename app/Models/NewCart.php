<?php

namespace App\Models;

//use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewCart extends Model
{
    use HasFactory;
    protected $table = 'new_carts';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'barang_id', 'quantity', 'sub_total'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function checkout()
    {
        return $this->belongsTo(Checkout::class, 'checkout_id', 'id');
    }
}
