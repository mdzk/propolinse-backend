<?php

namespace App\Http\Controllers\Produk;

use App\Models\Checkout;
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
    public function getUserCart()
    {
        $cart = NewCart::with('barang')
            ->where('users_id', auth()->user()->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $cart = $cart->filter(function ($item) {
            return !Checkout::where('newcart_id', $item->id)->exists();
        });

        return response()->json($cart, 200);
    }

    public function getUserCartHome()
    {
        $cart = NewCart::with('barang')
            ->where('users_id', auth()->user()->id)
            ->orderBy('updated_at', 'DESC')
            ->limit(2)
            ->get();

        $cart = $cart->filter(function ($item) {
            return !Checkout::where('newcart_id', $item->id)->exists();
        });

        return response()->json($cart, 200);
    }
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
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->barang_id;
        $quantity = $request->quantity;
        $user = auth()->user();

        $product = Barang::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        if ($product->stok < $quantity) {
            return response()->json(['message' => 'Stok barang tidak mencukupi.'], 400);
        }

        // Cek apakah ada NewCart yang sesuai
        $cartItem = NewCart::where('users_id', $user->id)
            ->where('barang_id', $productId)
            ->whereNotIn('id', function ($query) use ($productId) {
                $query->select('newcart_id')
                    ->from('checkouts')
                    ->join('new_carts', 'checkouts.newcart_id', '=', 'new_carts.id')
                    ->where('new_carts.barang_id', $productId);
            })
            ->orderBy('created_at', 'DESC')
            ->first();

        if ($cartItem) {
            // Jika sudah ada NewCart yang berelasi dengan Checkout, tambahkan quantity dan subtotal
            $cartItem->quantity += $quantity;
            $cartItem->sub_total += ($product->hrg_brg * $quantity);
            $cartItem->save();
        } else {
            // Jika belum ada NewCart yang berelasi dengan Checkout, buat NewCart baru
            $cartItem = NewCart::create([
                'users_id' => $user->id,
                'barang_id' => $productId,
                'quantity' => $quantity,
                'sub_total' => $product->hrg_brg * $quantity,
            ]);
        }

        return response()->json([
            'order' => $cartItem,
            'message' => 'Produk Berhasil Ditambahkan kedalam keranjang!',
        ], 201);
    }



    public function removeFromCart($id)
    {
        $cartItem = NewCart::where('id', $id)
            ->where('users_id', auth()->user()->id)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item tidak ditemukan dalam keranjang.'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => '1 Item berhasil dihapus dari keranjang.'], 200);
    }
}
