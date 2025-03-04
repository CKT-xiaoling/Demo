<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class IndexController
{
    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function dashboard()
    {
        return view('dashboard', [
            "name" => "dashboard"
        ]);
    }

    public function permission()
    {
        return view('index');
    }

    public function showDev()
    {
        if (auth()->user()->role == "admin") {
            return view('dev', [
                "name" => "dev"
            ]);
        }
        return $this->notPermission('dev');
    }

    public function user()
    {
        return view('user', [
            "name" => "user"
        ]);
    }

    public function login()
    {
        return view('login');
    }

    public function register()
    {
        if (auth()->user()->role == "admin") {
            return view('register', [
                "name" => "user"
            ]);
        }

        return $this->notPermission("user");
    }

    public function logout()
    {
        $user = request()->user();
        $user->tokens()->delete();
        request()->user()->tokens()->where('id', auth()->id())->delete();
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }

    public function download()
    {
        $file_name = request()->input('file_name');
        if ($file_name != "") {
            if (Storage::disk("public")->exists($file_name)) {
                return Storage::disk("public")->download($file_name);
            }
        }
        echo "文件不存在";
        return "";
    }

    private function notPermission($name)
    {
        return view('permission', [
            "name" => $name
        ]);
    }
}
