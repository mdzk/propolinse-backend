<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CodePasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\ResetPasswordRequest;



class ResetPasswordController extends Controller
{

    public function __invoke(ResetPasswordRequest $request)
    {
        $passwordReset = CodePasswordReset::firstWhere('code', $request->code);

        if ($passwordReset->isExpire()) {
            return $this->jsonResponse(null, trans('passwords.code_is_expire'), 422);
        }

        $user = User::firstWhere('email', $passwordReset->email);

        // $user->update($request->only('password'));
        $user->update([
            "password" => Hash::make($request->password)
        ]);

        $passwordReset->where('code', $request->code)->delete();

        //return $this->jsonResponse(null, trans('site.password_has_been_successfully_reset'), 200);
        return response()->json([
            null, trans('site.password_has_been_successfully_reset'), 200
        ]);
    }
}
