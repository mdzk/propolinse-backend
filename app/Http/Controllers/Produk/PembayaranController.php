<?php

namespace App\Http\Controllers\Produk;

use App\Models\NewCart;
use App\Models\Checkout;
use App\Models\Konfirmasi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PembayaranController extends Controller
{
    /*public function inputbayar(Request $request)
    {
        $this->validate($request, [
            'co_id' => 'required',
            'nm_pengirim' => 'required',
            'no_rek' => 'required',
            'jmlh_transfer' => 'required',
            'bank' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $image = Pembayaran::create([
            'image'     => $image->hashName(),
            'nm_pengirim'     => $request->nm_pengirim,
            'no_rek'     => $request->no_rek,
            'jmlh_transfer'     => $request->jmlh_transfer,
            'bank'     => $request->bank,
            'co_id'     => $request->co_id,

        ]);


        //return response($image, Response::HTTP_CREATED);

        return [
            'message' => 'Pembayaran diproses'
        ];
    }*/

    public function konfirmasi(Request $request, $cartId)
    {

        $validator = Validator::make($request->all(), [
            'bayar_id' => 'required',
            'konfirm' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ada kesalahan!',
                'data' => $validator->errors()
            ]);
        }

        $input = $request->all();
        $konfir = Konfirmasi::create($input);

        //$success['token'] =  $user->createToken('auth_token')->plainTextToken;
        //$success['email'] =  $user->email;

        return response()->json([
            'success' => true,
            'message' => 'Lunas',
            'data' => $konfir
        ]);
    }

    public function showorder()
    {

        $data = DB::table('checkouts')
            ->join('konfirmasi', 'checkouts.id', '=', 'konfirmasi.id')
            ->join('new_carts', 'checkouts.id', '=', 'new_carts.id')
            ->join('barang', 'checkouts.id', '=', 'barang.id')
            //->where('konfirmasi.id', '=', 4) // Kondisi WHERE pada tabel comments
            ->select('checkouts.*', 'new_carts.*', 'konfirmasi.*', 'barang.nm_brg')
            ->get();

        return response()->json($data, 404);
    }

    public function jumlahpesanan()
    {
        // Menggunakan Query Builder untuk menghitung jumlah Pesanan
        $jumlah = DB::table('pembayaran')->count();

        return response()->json(['Pesanan' => $jumlah]);
    }

    public function jumlahpembayaran()
    {
        // Menggunakan Query Builder untuk menghitung jumlah Pembayaran
        $jumlah = DB::table('konfirmasi')->count();

        return response()->json(['Konfrimasi' => $jumlah]);
    }
}
