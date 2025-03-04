<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

class ApiController
{
    function userList()
    {
        $page = request()->get("page", 1);
        $limit = request()->get("limit", 10);
        $page = ($page - 1) * $limit;

        $count = User::count();
        $list = User::limit($limit)->offset($page)->get();

        $data = [
            "code"  => 0,
            "msg"   => "",
            "count" => $count,
            "data"  => $list,
        ];

        return response()->json($data);
    }

    function updateUser()
    {
        $id = request()->get('id', 0);
        $role = request()->get('role', "user");
        if ($id) {
            if ($role != "admin") {
                $role = "user";
            }

            User::where("id", $id)->update(["role" => $role]);
            return response()->json(['message' => '修改成功']);
        }

        return response()->json(['message' => '修改失败'], 401);
    }
}
