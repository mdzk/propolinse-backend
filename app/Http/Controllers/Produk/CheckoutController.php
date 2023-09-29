<?php

namespace App\Http\Controllers\Produk;

use App\Models\Barang;
use App\Models\NewCart;
use App\Models\Checkout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function inputcheckout2(Request $request)
    {
        // return response()->json([
        //     'data' => $request->all(),
        // ], 201);
        $validated = $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'kode_pos' => 'nullable',
            'pengiriman' => 'required',
            'ongkir' => 'required',
            'bank' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5048',
        ]);

        $productIdsString = $request->input('productIds'); // Mengambil data sebagai string JSON
        $productIds = json_decode($productIdsString, true);
        $nama = $request->input('nama');
        $alamat = $request->input('alamat');
        $kode_pos = $request->input('kode_pos');
        $pengiriman = $request->input('pengiriman');
        $ongkir = $request->input('ongkir');
        $bank = $request->input('bank');
        $image = $request->file('image');

        $totalBayar = NewCart::whereIn('id', $productIds)->sum('sub_total');

        $totalBayar += $ongkir;

        foreach ($productIds as $productId) {
            $cartItem = new Checkout();
            $cartItem->newcart_id = $productId;
            $cartItem->nama = $nama;
            $cartItem->alamat = $alamat;
            $cartItem->kode_pos = $kode_pos;
            $cartItem->pengiriman = $pengiriman;
            $cartItem->ongkir = $ongkir;
            $cartItem->bank = $bank;
            $cartItem->status = 'pending';

            $image->storeAs('public/posts', $image->hashName());
            $cartItem->image = $image->hashName();

            $cartItem->total_bayar = $totalBayar;

            $cartItem->save();
        }

        return response()->json([
            'message' => 'Transaksi Anda sedang diproses.',
        ], 201);
    }
}
