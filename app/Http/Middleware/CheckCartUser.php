<?php

namespace App\Http\Middleware;


use Closure;
use App\Models\NewCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckCartUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/CheckCartOwner.php

    public function handle($request, Closure $next)
    {
        // Dapatkan pengguna yang terotentikasi berdasarkan token Sanctum
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            // Dapatkan ID pengguna
            $userId = $user->id;

            // Lakukan pengecekan jika ada data keranjang untuk pengguna ini
            $cart = DB::table('new_carts')->where('users_id', $userId)->first();

            if (!$cart) {
                // Jika belum ada data keranjang untuk pengguna, buat data baru
                DB::table('new_carts')->insert(['users_id' => $userId]);
            }
        }

        return $next($request);
    }
}
