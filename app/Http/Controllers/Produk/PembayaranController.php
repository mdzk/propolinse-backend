<?php

namespace App\Http\Controllers\Produk;

use App\Models\Barang;
use App\Models\NewCart;
use App\Models\Checkout;
use App\Models\Konfirmasi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PembayaranController extends Controller
{
    public function konfirmasi(Request $request, $cartId)
    {
        if (Konfirmasi::where('bayar_id', $cartId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Data sudah ada',
            ], 400);
        }

        $konfir = Konfirmasi::create([
            'konfirm' => 'confirmed',
            'bayar_id' => $cartId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lunas',
            'data' => $konfir
        ], 200);
    }

    public function pesanan()
    {
        $data = Checkout::leftJoin('konfirmasi', 'checkouts.id', '=', 'konfirmasi.bayar_id')
            ->join('new_carts', 'checkouts.newcart_id', '=', 'new_carts.id')
            ->join('barang', 'new_carts.barang_id', '=', 'barang.id')
            ->select(
                'checkouts.id as checkout_id',
                'checkouts.nama',
                'checkouts.alamat',
                'checkouts.kode_pos',
                'barang.nm_brg',
                'new_carts.quantity',
                'new_carts.sub_total',
                'checkouts.pengiriman',
                'checkouts.total_bayar',
                'checkouts.bank',
                'checkouts.image',
                'checkouts.created_at'
            )
            ->whereNull('konfirmasi.id')
            ->get();

        return response()->json($data, 200);
    }

    public function pembayaran()
    {
        $data = Checkout::join('konfirmasi', 'checkouts.id', '=', 'konfirmasi.bayar_id')
            ->join('new_carts', 'checkouts.newcart_id', '=', 'new_carts.id')
            ->join('barang', 'new_carts.barang_id', '=', 'barang.id')
            ->select('checkouts.*', 'new_carts.*', 'konfirmasi.*', 'barang.nm_brg')
            ->get();

        return response()->json($data, 200);
    }

    public function bestseller()
    {
        $joinQuery = Checkout::join('new_carts', 'checkouts.newcart_id', '=', 'new_carts.id')
            ->join('barang', 'new_carts.barang_id', '=', 'barang.id')
            ->select('barang.nm_brg', 'barang.hrg_brg', 'barang.id as barang_id', 'barang.image as barang_image');

        $desiredCount = 10;

        $randomData = Barang::inRandomOrder()
            ->select('nm_brg', 'hrg_brg', 'id as barang_id', 'image as barang_image')
            ->limit($desiredCount)
            ->get();

        $data = $joinQuery->get()->concat($randomData);

        $uniqueData = $data->unique('barang_id')->values()->all();

        return response()->json($uniqueData, 200);
    }

    public function jumlahpesanan()
    {
        $jumlah = DB::table('checkouts')->count();
        return response()->json(['Pesanan' => $jumlah]);
    }

    public function dashboard()
    {
        $produk     = Barang::count();
        $pelanggan     = User::where('role', 'user')->count();
        $pesanan = Checkout::leftJoin('konfirmasi', 'checkouts.id', '=', 'konfirmasi.bayar_id')
            ->join('new_carts', 'checkouts.newcart_id', '=', 'new_carts.id')
            ->join('barang', 'new_carts.barang_id', '=', 'barang.id')
            ->whereNull('konfirmasi.id')
            ->count();
        $pembayaran = Checkout::join('konfirmasi', 'checkouts.id', '=', 'konfirmasi.bayar_id')
            ->join('new_carts', 'checkouts.newcart_id', '=', 'new_carts.id')
            ->join('barang', 'new_carts.barang_id', '=', 'barang.id')
            ->select('checkouts.*', 'new_carts.*', 'konfirmasi.*', 'barang.nm_brg')
            ->count();

        return response()->json([
            'produk' => $produk,
            'pesanan' => $pesanan,
            'pembayaran' => $pembayaran,
            'pelanggan' => $pelanggan
        ]);
    }

    public function jumlahpembayaran()
    {
        $jumlah = DB::table('konfirmasi')->count();
        return response()->json(['Konfrimasi' => $jumlah]);
    }
}
