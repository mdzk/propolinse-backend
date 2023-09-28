<?php

namespace App\Http\Controllers\Produk;

use Produk;
use App\Order;

use App\Product;
use App\Models\Cart;
use App\Models\Barang;
use App\Models\NewCart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CartItemResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Console\View\Components\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\CartItemCollection as CartItemCollection;
use App\Http\Resources\ItemCartCollection as ItemCartCollection;
use App\Models\User;

class CartController extends Controller
{
    public function show($id)
    {
        $cart = NewCart::find($id);

        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        if ($cart->user->id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($cart, 200);
    }

    public function keranjang(Request $request)
    {
        $productId = $request->input('barang_id');
        $quantity = $request->input('quantity');
        $subtotal = $request->input('sub_total');
        $request['users_id'] = auth()->user()->id;

        $product = Barang::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        $subtotal = $product->hrg_brg * $quantity;

        $cartItem = new NewCart($request->all());
        $cartItem->barang_id = $productId;
        $cartItem->quantity = $quantity;
        $cartItem->sub_total = $subtotal;
        $cartItem->save();

        //$cartItem = NewCart::create($request->all());
        return response()->json([
            'order' => $cartItem,
            'message' => 'Produk Berhasil Ditambahkan kedalam keranjang!',
        ], 201);
    }

    public function removeFromCart($id)
    {
        $cartItem = NewCart::find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Item tidak ditemukan dalam keranjang.'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => '1 Item berhasil dihapus dari keranjang.'], 200);
    }
}
