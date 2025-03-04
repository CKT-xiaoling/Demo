@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://public.bookv.cn/addin/layui/layui-v2.8.12/css/layui.css" rel="stylesheet">
    <script src="https://public.bookv.cn/addin/layui/layui-v2.8.12/layui.js"></script>
    <script src="https://public.bookv.cn/js/jquery/jquery-3.5.1.min.js"></script>
</head>
<body>
<div>
    <ul class="layui-nav" lay-filter="">
        <li class="layui-nav-item {{ $name == 'dashboard' ? 'layui-this' : '' }}"><a href="/dashboard">控制台</a></li>
        <li class="layui-nav-item {{ $name == 'dev' ? 'layui-this' : '' }}"><a href="/dev">列表</a></li>
        <li class="layui-nav-item {{ $name == 'user' ? 'layui-this' : '' }}"><a href="/user">用户</a></li>
        <li class="layui-nav-item" style="float: right">
            <a href="#">{{ $user?$user->name:"" }}</a>
            <dl class="layui-nav-child">
                <dd><a href="/logout">退出</a></dd>
            </dl>
        </li>
    </ul>
</div>
<div class="container">
    @yield('content')
</div>
</body>
<script>
    //注意：导航 依赖 element 模块，否则无法进行功能性操作
    layui.use('element', function () {
        var element = layui.element;

        //…
    });
</script>
</html>
