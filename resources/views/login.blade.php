<html>
    <head>
        <title>登陆</title>
    </head>
    <body>
        <button id="wechat_login">微信授权登陆</button>
    </body>
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script>
        $(function(){
            $('#wechat_login').click(function(){
                window.location.href = '{{url('wechat/login')}}';
            });
        });
    </script>
</html>