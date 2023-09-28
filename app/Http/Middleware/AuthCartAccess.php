<?php

namespace App\Http\Middleware;

use App\Models\NewCart;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCartAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $cartId = $request->route('id'); // Anda perlu mendapatkan ID keranjang dari permintaan sesuai dengan rute Anda
        $cart = NewCart::find($cartId);

        if ($cart && $cart->user_id === auth()->user()->id) {
            return $next($request);
        }

        return response()->json(['error' => 'Un'], 403);
    }
}
