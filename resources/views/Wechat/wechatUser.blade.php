<html>
<head>
    <title>用户列表</title>
</head>
<body>
    <form action="{{url('wechat/add_user_tag')}}" method="post">
        @csrf
        <input type="hidden" name="tag_id" value="{{$tag_id}}">
        <table border="1">
            <tr>
                <td></td>
                <td>openid</td>
                <td>操作</td>
            </tr>
            @foreach($data as $v)
            <tr>
                <td><input type="checkbox" name="opneid_list[]" value="{{$v}}"></td>
                <td>{{$v}}</td>
                <td><a href="{{url('wechat/user_tag')}}?openid={{$v}}">查看用户标签</a></td>
            </tr>
                @endforeach
        </table>

        <input type="submit" value="提交">
    </form>
</body>
</html>