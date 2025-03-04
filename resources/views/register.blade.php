@extends('index')
@section('content')
    <style>
        .container {
            display: flex;
            justify-content: center; /* 水平居中 */
            align-items: center; /* 垂直居中 */
            height: 100vh; /* 设置容器高度 */
        }
    </style>
    <div class="container">
        <div style="margin-top: 20px">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入用户名"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">密码框</label>
                    <div class="layui-input-inline">
                        <input type="password" name="password" required lay-verify="required" placeholder="请输入密码"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">邮件</label>
                    <div class="layui-input-inline">
                        <input type="text" name="email" required lay-verify="required" placeholder="请输入用户名"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">权限</label>
                    <div class="layui-input-inline">
                        <select name="role" lay-verify="required">
                            <option value=""></option>
                            <option value="user">user</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        //Demo
        layui.use('form', function () {
            var form = layui.form;
            //监听提交
            form.on('submit(formDemo)', function (data) {
                $.post('/api/register', data.field)
                    .done(function (data) {
                        layer.msg(data.message);
                        window.location.href = "/user"
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        // 请求失败，处理错误
                        if (jqXHR.status === 404) {
                            layer.msg('错误 404: 找不到请求的资源');
                        } else {
                            layer.msg(jqXHR.responseJSON.message);
                        }
                    });
                return false;
            });
        });
    </script>
@endsection
