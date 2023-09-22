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

class CartController extends Controller
{


    public function show(Request $request, NewCart $cart, $id)
    {
        $cart = NewCart::with('barang')->where('id', $id)->first();

        //return CartItemResource::collection($cart);
        //$cart = NewCart::findOrfail($id);
        return response()->json($cart);
        //return response()->json(['total_harga' => $totalHarga]);
    }



    public function keranjang(Request $request)
    {
        $productId = $request->input('barang_id');
        $quantity = $request->input('quantity');

        $product = Barang::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        $subtotal = $product->hrg_brg * $quantity;

        $cartItem = new NewCart();
        $cartItem->barang_id = $productId;
        $cartItem->quantity = $quantity;
        $cartItem->sub_total = $subtotal;
        $cartItem->save();

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





/**
 * checkout the cart Items and create and order.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \App\Cart  $cart
 * @return void
 */
/**public function checkout(Cart $cart, Request $request)
    {

        if (Auth::guard('api')->check()) {
            $userID = auth('api')->user()->getKey();
        }

        $validator = Validator::make($request->all(), [
            'cartKey' => 'required',
            'name' => 'required',
            'adress' => 'required',
            'credit card number' => 'required',
            'expiration_year' => 'required',
            'expiration_month' => 'required',
            'cvc' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $cartKey = $request->input('cartKey');
        if ($cart->key == $cartKey) {
            $name = $request->input('name');
            $adress = $request->input('adress');
            $creditCardNumber = $request->input('credit card number');
            $TotalPrice = (float) 0.0;
            $items = $cart->items;

            foreach ($items as $item) {

                $product = Product::find($item->product_id);
                $price = $product->price;
                $inStock = $product->UnitsInStock;
                if ($inStock >= $item->quantity) {

                    $TotalPrice = $TotalPrice + ($price * $item->quantity);

                    $product->UnitsInStock = $product->UnitsInStock - $item->quantity;
                    $product->save();
                } else {
                    return response()->json([
                        'message' => 'The quantity you\'re ordering of ' . $item->Name .
                            ' isn\'t available in stock, only ' . $inStock . ' units are in Stock, please update your cart to proceed',
                    ], 400);
                }
            }**/

/**
 * Credit Card information should be sent to a payment gateway for processing and validation,
 * the response should be dealt with here, but since this is a dummy project we'll
 * just assume that the information is sent and the payment process was done succefully,
 */

/**$PaymentGatewayResponse = true;
            $transactionID = md5(uniqid(rand(), true));

            if ($PaymentGatewayResponse) {
                $order = Order::create([
                    'products' => json_encode(new CartItemCollection($items)),
                    'totalPrice' => $TotalPrice,
                    'name' => $name,
                    'address' => $adress,
                    'userID' => isset($userID) ? $userID : null,
                    'transactionID' => $transactionID,
                ]);

                $cart->delete();

                return response()->json([
                    'message' => 'you\'re order has been completed succefully, thanks for shopping with us!',
                    'orderID' => $order->getKey(),
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'The CarKey you provided does not match the Cart Key for this Cart.',
            ], 400);
        }**/
