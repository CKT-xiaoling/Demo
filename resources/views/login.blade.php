<html>
<link href="https://public.bookv.cn/addin/layui/layui-v2.8.12/css/layui.css" rel="stylesheet">
<style>
    .container {
        display: flex;
        justify-content: center; /* 水平居中 */
        align-items: center; /* 垂直居中 */
        height: 100vh; /* 设置容器高度 */
    }
</style>

<div class="container">
    <div style="width: 500px">
        <form class="layui-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="layui-form-item">
                <label class="layui-form-label">账号</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" required lay-verify="required" placeholder="请输入账号"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">密码</label>
                <div class="layui-input-inline">
                    <input type="password" name="password" required lay-verify="required" placeholder="请输入密码"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="formDemo">登录</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://public.bookv.cn/addin/layui/layui-v2.8.12/layui.js"></script>
<script src="https://public.bookv.cn/js/jquery/jquery-3.5.1.min.js"></script>
<script>
    layui.use('form', function () {
        var form = layui.form;

        //监听提交
        form.on('submit(formDemo)', function (data) {
            $.post('/api/login', data.field)
                .done(function (data) {
                    window.location.href = "/dashboard"
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    // 请求失败，处理错误
                    if (jqXHR.status === 404) {
                        console.log('错误 404: 找不到请求的资源');
                    } else {
                        layer.msg(jqXHR.responseJSON.message);
                    }
                });
            return false;
        });
    });
</script>

</html>
