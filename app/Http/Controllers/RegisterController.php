<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'nama_akhir' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'tgl_lhr' => 'required',
            'gender' => 'required',
            'alamat' => 'required|max:255',
            'confirm_password' => 'required|same:password',
        ]);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['email'] =  $user->email;

        return response()->json([
            'success' => true,
            'message' => 'anda berhasil mendaftarkan akun',
            'data' => $success
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            $success['token'] =  $auth->createToken($auth->name)->plainTextToken;
            $success['name'] = $auth->name;

            return response()->json([
                'success' => true,
                'message' => 'anda berhasil melakukan login',
                'data' => $success
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'periksa kembali email dan password anda!',
                'data' => null
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'nama_akhir' => 'required',
            'email' => 'required|email',
            'tgl_lhr' => 'required',
            'gender' => 'required',
            'password' => 'sometimes|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'sometimes|required_with:password',
        ]);

        $post = User::find($id);

        $updates = [
            'name' => $request->name,
            'nama_akhir' => $request->nama_akhir,
            'email' => $request->email,
            'tgl_lhr' => $request->tgl_lhr,
            'gender' => $request->gender,
        ];

        if (!empty($request->password)) {
            $updates['password'] = $request->password;
        }

        $post->update($updates);

        return [
            'message' => 'Berhasil update profil'
        ];
    }




    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'You have successfully logged out'
        ];
    }
}
