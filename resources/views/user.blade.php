@extends('index')
@section('content')
    <table id="user" lay-filter="user"></table>
    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <a href="/register">
                <button class="layui-btn layui-btn-sm" lay-event="getCheckData">添加用户</button>
            </a>

        </div>
    </script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    </script>
    <script>
        layui.use('table', function () {
            var table = layui.table;

            //第一个实例
            table.render({
                elem: '#user'
                , height: 500
                , url: "/api/user_list"
                , toolbar: '#toolbarDemo'
                , data: [{}]
                , page: true //开启分页
                , cols: [[ //表头
                    {field: 'id', title: 'ID', width: 80, sort: true, fixed: 'left'}
                    , {field: 'name', title: '用户名', width: 300}
                    , {field: 'email', title: '邮件', width: 200}
                    , {field: 'role', title: '权限', event: 'role', width: 100}
                ]]
            });

            //监听工具条
            table.on('tool(user)', function (obj) {
                var data = obj.data;
                if (obj.event === 'role') {
                    layer.prompt({
                        formType: 3
                        , title: '修改 ' + data.name + ' 的用户权限'
                        , value: data.role
                    }, function (value, index) {
                        layer.close(index);
                        if (value !== "admin") value = "user"
                        if (value === data.role) return false
                        //这里一般是发送修改的Ajax请求
                        $.post('/api/update_user', {"id": data.id, "role": value})
                            .done(function (data) {
                                layer.msg(data.message);
                            })
                            .fail(function (jqXHR, textStatus, errorThrown) {
                                // 请求失败，处理错误
                                if (jqXHR.status === 404) {
                                    layer.msg('错误 404: 找不到请求的资源');
                                } else {
                                    layer.msg(jqXHR.responseJSON.message);
                                }
                            });
                        //同步更新表格和缓存对应的值
                        obj.update({
                            role: value
                        });
                    });
                }
            });
        });
    </script>
@endsection
