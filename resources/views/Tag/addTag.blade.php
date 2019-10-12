<html>
    <head>
        <title>添加标签</title>
    </head>
    <body>
    <form action="{{url('wechat/do_add_tag')}}" method="post">
        @csrf
        标签名称：<input type="text" name="tag_name">
        <input type="submit" value="提交">
    </form>
    </body>
</html>