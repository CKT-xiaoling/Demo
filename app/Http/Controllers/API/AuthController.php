<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login()
    {
        $status = false;
        $credentials = request(['name', 'password']);

        try {
            if (Auth::attempt($credentials, true)) {
                $status = true;
            }

            if ($status) {
                $key = 'user:' . Auth::id() . ':accessToken';
                $token = Cache::get($key);
                if (!$token) {
                    // $token = auth()->attempt($credentials);
                    $token = request()->user()->createToken("dome")->plainTextToken;
                    Cache::put($key, $token);
                }

                $data = [
                    'userAbilities' => [[
                        'action'  => 'manage',
                        'subject' => 'all',
                    ]],
                    'userData'      => request()->user(),
                    'accessToken'   => $token,
                ];
                return response()->json($data)->cookie("accessToken", $token);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }

        return response()->json(['message' => '登录失败，账号或密码错误'], 401);
    }

    public function logout()
    {
        Auth::logout();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4',
            'role'     => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->all()[0]], 401);
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return response()->json(['message' => "注册成功"]);
    }
}
