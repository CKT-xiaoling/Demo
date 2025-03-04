@extends('index')
@section('content')
    <table id="dev" lay-filter="dev"></table>
    <script type="text/html" id="toolbarDemo">
        <div style="display: inline-flex">
            <form class="layui-form" action="">
                <div class="layui-btn-inline" style="display: inline-flex">
                    <input type="text" name="sql" lay-verify="required" autocomplete="off" placeholder="请输入SQL语句"
                           class="layui-input" required style="width: 300px">
                    <button class="layui-btn" lay-filter="query" lay-submit style="margin-left: 10px">查询</button>
                    {{--                    <button class="layui-btn" lay-filter="show_list" lay-submit style="margin-left: 10px">显示列表--}}
                    {{--                    </button>--}}
                    <button class="layui-btn" lay-filter="export_excel" lay-submit style="margin-left: 10px">导出Excel
                    </button>
                    <button class="layui-btn" lay-filter="export_json" lay-submit style="margin-left: 10px">导出json
                    </button>
                </div>
            </form>
        </div>
    </script>
    <script>
        layui.use(['table', 'form'], function () {
            var table = layui.table;
            var form = layui.form;

            //第一个实例
            var tableList = table.render({
                elem: '#dev'
                , height: 500
                , toolbar: '#toolbarDemo'
                , data: [{}]
                , page: true //开启分页
                , cols: [[ //表头
                    {field: 'id', title: 'ID', width: 80, sort: true, fixed: 'left'}
                    , {field: 'name', title: '音乐', width: 300}
                    , {field: 'audio_name', title: '作者', width: 300}
                    , {field: 'file_size', title: '大小', width: 200}
                    , {field: 'hash', title: '哈希值', width: 300}
                    , {field: 'time_length', title: '时长', width: 200}
                ]]
            });

            form.on('submit(query)', function (data) {
                tableList.reload({
                    url: "/api/music_list",
                    where: data.field,
                    method: "post",
                    error: function (e, msg) {
                        layer.msg(e.responseJSON.msg)
                    },
                    page: {
                        curr: 1, // 重置到第一页
                    }
                })
                return false;
            });

            form.on('submit(show_list)', function (data) {
                tableList.reload({
                    url: "/api/music_list",
                    method: "post",
                })
                return false;
            });

            form.on('submit(export_excel)', function (data) {
                $.post('/api/music_list', {"sql": data.field.sql, "action": "excel"})
                    .done(function (data) {
                        console.log(data)
                        window.open('/download?file_name=' + data['data'], '_blank');
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        // 请求失败，处理错误
                        if (jqXHR.status === 404) {
                            layer.msg('错误 404: 找不到请求的资源');
                        } else {
                            layer.msg(jqXHR.responseJSON.msg);
                        }
                    });
                return false;
            });

            form.on('submit(export_json)', function (data) {
                $.post('/api/music_list', {"sql": data.field.sql, "action": "json"})
                    .done(function (data) {
                        console.log(data)
                        window.open('/download?file_name=' + data['data'], '_blank');
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        // 请求失败，处理错误
                        if (jqXHR.status === 404) {
                            layer.msg('错误 404: 找不到请求的资源');
                        } else {
                            layer.msg(jqXHR.responseJSON.msg);
                        }
                    });

                return false;
            });
        });
    </script>
@endsection
