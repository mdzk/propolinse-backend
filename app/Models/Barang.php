<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $fillable = ['kd_brg', 'hrg_brg', 'stok', 'nm_brg', 'tag', 'type_size', 'ket_brg', 'desk_umum', 'berat_brg', 'jenis_brg', 'image'];
}
