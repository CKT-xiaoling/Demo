<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login()
    {
        $status = false;
        $credentials = request(['account', 'password']);

        try{
            if (Auth::attempt($credentials, true)) {
                $status = true;
            } else {
                $credentials['phone'] = $credentials['account'];
                unset($credentials['account']);
                if (Auth::attempt($credentials, true)) {
                    $status = true;
                }
            }

            if ($status) {
                $key = 'user:' . Auth::id() . ':accessToken';
                $token = Cache::get($key);
                if (!$token) {
                    $token = auth()->attempt($credentials);
                    Cache::put($key, $token);
                }

                $data = [
                    'userAbilities' => [[
                        'action'  => 'manage',
                        'subject' => 'all',
                    ]],
                    'userData'      => request()->user(),
                    'accessToken'   => $token,
                    'token_type'    => 'bearer',
                ];
                return response()->json($data);
            }
        }catch(\Exception $e){
            return response()->json(['errors' => ['account' => '登录失败，账号或密码错误']], 401);
        }

        return response()->json(['errors' => ['account' => '登录失败，账号或密码错误']], 401);
    }

    public function logout()
    {
        Auth::logout();
        return Tool::succeed_msg('退出成功');
    }

    public function refresh()
    {

    }
}
