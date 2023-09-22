<?php

namespace App\Http\Resources;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemCartCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\CartItemResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
