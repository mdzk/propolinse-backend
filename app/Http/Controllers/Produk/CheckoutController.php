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
        $validated = $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'kode_pos' => 'required',
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

        foreach ($productIds as $productId) {
            // Ambil data dari keranjang
            $cartItem = NewCart::find($productId);

            // Buat transaksi baru
            $checkoutItem = new Checkout();
            $checkoutItem->newcart_id = $productId;
            $checkoutItem->nama = $nama;
            $checkoutItem->alamat = $alamat;
            $checkoutItem->kode_pos = $kode_pos;
            $checkoutItem->pengiriman = $pengiriman;
            $checkoutItem->ongkir = $ongkir;
            $checkoutItem->bank = $bank;

            $image->storeAs('public/posts', $image->hashName());
            $checkoutItem->image = $image->hashName();

            // Mengambil sub_total dari produk yang dibeli dan menambahkannya dengan ongkir
            $checkoutItem->total_bayar = $cartItem->sub_total + $ongkir;

            $checkoutItem->save();

            $product = Barang::find($cartItem->barang_id);
            $stokBaru = $product->stok - $cartItem->quantity;
            $product->stok = $stokBaru;
            $product->save();
        }

        return response()->json([
            'message' => 'Transaksi Anda sedang diproses.',
        ], 201);
    }
}
