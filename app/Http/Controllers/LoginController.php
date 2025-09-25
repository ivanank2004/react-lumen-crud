<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class LoginController extends Controller
{
    public function register(Request $request){
        $this->validate($request, [
            "username"=> "required",
            "email"=> "required",
            "password"=> "required",
        ]);

        $data = [
            "username"=> $request->input("username"),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ];

        User::create($data);

        return response()->json($data);
    }

    public function login(Request $request){
            $username = $request->input('username');
            $password = $request->input('password');

            $user = User::where('username', $username)->first();

            if($user && Hash::check($password, $user->password)){
                $token = Str::random(40);

                $user->update([
                    'api_token' => $token,
                ]);

                return response()->json([
                    'status'=> 'success',
                    'token' => $token,
                    'data' => $user,
                ]);
            } else if ($user->password != $password){
                    return response()->json([
                        'status'=> 'error',
                        'message' => 'Username atau Password salah.',
                ], 401);
            } else {
                return response()->json([
                    'status'=> 'error',
                    'data' => '',
                ]);
            }
    }

    public function logout(Request $request){
        $token = $request->header('Authorization');

        if (!$token) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Token tidak ditemukan'
        ], 401);
    }

    $user = User::where('api_token', $token)->first();

    if (!$user) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Token tidak valid'
        ], 401);
    }

    $user->update(['api_token' => null]);

    return response()->json([
        'status'=> 'success',
        'message'=> 'Anda berhasil Logout',
    ]);
    }
}
