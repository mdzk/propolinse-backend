<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Mail\SendCodeResetPassword;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\CodePasswordReset;

class ForgotPaswordController extends Controller
{

    //Mengirim Kode untuk reset password (bag 1)

    public function __invoke(ForgotPasswordRequest $request)
    {
        CodePasswordReset::where('email', $request->email)->delete();

        $codeData = CodePasswordReset::create($request->data());

        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        //return $this->jsonResponse(null, trans('passwords.sent'), 200);

        return response()->json([
            null, trans('passwords.sent'), 200
        ]);
    }
}
