<?php

namespace App\Http\Resources;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $barang = Barang::find($this->barang_id);

        return [
            'Barang_id' => $this->barang_id,
            'jenis_brg' =>  $barang->jenis_brg,
            'hrg_brg' => $barang->hrg_brg,
            'Name' => $barang->nm_brg,
            'Quantity' => $this->quantity,
        ];
    }
}
