<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Tools\Tools;

class WechatController extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function wechat_user(Request $request)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->tools->get_access_token().'&next_openid=';
        $re = $this->tools->curl_get($url);
        $result = json_decode($re,1);
        return view('Wechat.wechatUser',['data'=>$result['data']['openid'],'tag_id'=>$request->input('tag_id')]);
    }

    public function index()
    {
       //$info = file_get_contents('http://wechat.18022480300.com/wechat/index');
       $access_token = $this->tools->get_access_token();
       echo $access_token;
    }



}
