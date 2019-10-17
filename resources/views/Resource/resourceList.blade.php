<html>
    <head>
        <title>资源列表</title>
    </head>
    <body>
        <center>
            <button  type="button"  @if($type == 1)style="background-color: #1c94c4"@endif onclick="click_btn(1)">图片</button>
            <button onclick="click_btn(2)" type="button" @if($type == 2)style="background-color: #1c94c4"@endif>音频</button>
            <button onclick="click_btn(3)" type="button" @if($type == 3)style="background-color: #1c94c4"@endif>视频</button>
            <button onclick="click_btn(4)" type="button"  @if($type == 4)style="background-color: #1c94c4"@endif>缩略图</button>
            <br/><br/>

            <table border="1">
                <tr>
                    <td>ID</td>
                    <td>media_id</td>
                    <td>path</td>
                    <td>添加时间</td>
                    <td>操作</td>
                </tr>
                @foreach($info as $v)
                <tr>
                    <td>{{$v->id}}</td>
                    <td>{{$v->media_id}}</td>
                    <td>{{$v->path}}</td>
                    <td>{{date('Y-m-d H:i:s',$v->addtime)}}</td>
                    <td></td>
                </tr>
                @endforeach
            </table>
        </center>
        <script src="{{asset('js/jquery.min.js')}}"></script>
        <script>

            $(function(){

            });

            var click_btn = function(type){
                window.location.href = '{{url('/wechat/resource_list')}}?type='+type;
            }
        </script>
    </body>
</html>