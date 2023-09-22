<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\CodePasswordReset;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CodeCheckRequest;

class CodeCheckController extends Controller
{
    /**
     * @param  mixed $request
     * @return void
     */
    public function __invoke(CodeCheckRequest $request)
    {
        $passwordReset = CodePasswordReset::firstWhere('code', $request->code);

        if ($passwordReset->isExpire()) {
            return $this->jsonResponse(null, trans('passwords.code_is_expire'), 422);
        }

        //return $this->jsonResponse(['code' => $passwordReset->code], trans('passwords.code_is_valid'), 200);

        return response()->json([
            'code' => $passwordReset->code,
            trans('passwords.code_is_valid'), 200,

        ]);
    }
}
