<html>
<head>
    <title>添加标签</title>
</head>
<body>
<form action="{{url('wechat/do_update_tag')}}" method="post">
    @csrf
    <input type="hidden" name="tag_id" value="{{$tag_id}}">
    标签名称：<input type="text" name="tag_name" value="{{$tag_name}}">
    <input type="submit" value="提交">
</form>
</body>
</html>