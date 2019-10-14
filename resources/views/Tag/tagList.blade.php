<html>
    <head>
        <title>标签列表</title>
    </head>
    <body>
        <center>
            <button id="add_btn">添加</button>
            <button id="list_btn">粉丝列表</button>
            <br/>
            <br/>
            <table border="1">
                <tr>
                    <td>标签ID</td>
                    <td>标签名称</td>
                    <td>操作</td>
                </tr>
                @foreach($data as $v)
                <tr>
                    <td>{{$v['id']}}</td>
                    <td>{{$v['name']}}</td>
                    <td>
                        <a href="{{url('/wechat/del_tag')}}?tag_id={{$v['id']}}">删除</a> |
                        <a href="{{url('/wechat/update_tag')}}?tag_id={{$v['id']}}&tag_name={{$v['name']}}">修改</a> |
                        <a href="{{url('/wechat/wechat_user')}}?tag_id={{$v['id']}}">给用户打标签</a> |
                        <a href="{{url('/wechat/push_tag_msg')}}?tag_id={{$v['id']}}">发送消息</a>
                    </td>
                </tr>
                @endforeach
            </table>
            <script src="{{asset('js/jquery.min.js')}}"></script>
            <script>
                $(function(){
                    $('#add_btn').click(function(){
                        window.location.href = '{{url('/wechat/add_tag')}}';
                    });
                });
                $(function(){
                    $('#list_btn').click(function(){
                        window.location.href = '{{url('/wechat/wechat_user')}}';
                    });
                });
            </script>
        </center>
    </body>
</html>