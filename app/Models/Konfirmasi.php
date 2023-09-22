<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konfirmasi extends Model
{
    use HasFactory;
    protected $table = 'konfirmasi';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'bayar_id', 'konfirm'];

    public function post()
    {
        return $this->belongsTo(NewCart::class);
    }

    public function parent()
    {
        return $this->belongsTo(Konfirmasi::class);
    }
}
