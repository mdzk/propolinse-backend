<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "hrg_brg" => $this->hrg_brg,
            "stok" => $this->stok,
            "nm_brg" => $this->nm_brg,
            "ket_brg" => $this->ket_brg,
            "desk_umum" => $this->$request->desk_umum,
            "image" => $this->image,
            "berat_brg" => $this->berat_brg,
            "jenis_brg" => $this->jenis_brg
        ];
    }
}
