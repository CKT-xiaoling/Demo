<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Common\excel\Excel;
use App\Models\Music;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    function musicList()
    {
        $sql = request()->get("sql", "");
        $action = request()->get("action", "");
        $page = request()->get("page", 1);
        $limit = request()->get("limit", 10);
        $page = ($page - 1) * $limit;

        if ($sql != "") {
            return $this->sqlQuery($sql, $page, $limit, $action);
        }

        $count = Music::count();
        $list = Music::limit($limit)->offset($page)->get();

        $data = [
            "code"  => 0,
            "msg"   => "",
            "count" => $count,
            "data"  => $list,
        ];

        return response()->json($data);
    }

    function sqlQuery($sql, $page, $limit, $action)
    {
        $data = [
            "code"  => 401,
            "msg"   => "SQL 语句错误",
            "count" => 0,
            "data"  => [],
        ];

        // 检测字符串是否以SELECT或者select开头
        $status = preg_match('/^SELECT/i', $sql);
        if ($status) {
            preg_match_all("/'([^']*)'/", $sql, $matches);
            $values = $matches[1];
            $new_sql = preg_replace("/'[^']*'/", "?", $sql);
            $count_sql = preg_replace("/SELECT\s+.*?\s+FROM/i", "SELECT COUNT(id) FROM", $new_sql);
            if ($action == '') {
                $new_sql = $new_sql . ' LIMIT ' . $page . ',' . $limit;
            }

            try {
                $data["msg"] = "";
                $data["code"] = 0;
                $lists = DB::select($new_sql, $values);
                $count = DB::select($count_sql, $values);
                $count = collect($count[0])->toArray();
                $list = [];
                foreach ($lists as $value) {
                    $list[] = collect($value)->toArray();
                }

                if ($action != '') {
                    switch ($action) {
                        case "excel":
                            $data["data"] = $this->export_excel($list);
                            break;
                        case "json":
                            $data["data"] = $this->export_json($list);
                            break;
                    }
                } else {
                    $data["count"] = current($count);
                    $data["data"] = $list;
                }

            } catch (\Exception $e) {
                $data["msg"] = $e->getMessage();
            }

            $records = date("Y-m-d H:i:s") . " 用户：" . Auth::user()->name . " 执行了查询SQL：" . $new_sql;
            if ($data["msg"] != "") {
                $records .= " 出现了错误：" . $data["msg"];
            }
            Storage::disk('logs')->append('sql.log', $records);
        }

        return response()->json($data, $data['code'] ?: 200);
    }

    function export_excel($list)
    {
        $dir = getcwd() . '/storage/';
        $file_name = "excel_" . time();
        Excel::init();
        Excel::all_setValue($list);
        Excel::save($dir . $file_name . '.xls');

        return $file_name . '.xls';
    }

    function export_json($list)
    {
        $jsonList = json_encode($list);
        $file_name = 'json_' . time() . '.txt';
        Storage::disk('public')->prepend($file_name, $jsonList);
        return $file_name;
    }
}
