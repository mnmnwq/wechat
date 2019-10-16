<html>
    <head>
        <title>上传资源</title>
    </head>
    <body>
        <center>
            <form action="{{url('wechat/do_upload')}}" method="post" enctype="multipart/form-data">
                @csrf
                类型：<select name="type" id="">
                    <option value="image">图片</option>
                    <option value="voice">音频</option>
                    <option value="video">视频</option>
                    <option value="thumb">缩略图</option>
                </select><br/><br/>
                <input type="file" name="rsource"><br/><br/>
                <input type="submit" value="提交">
            </form>
        </center>
    </body>
</html>