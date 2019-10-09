<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WechatController extends Controller
{
    public function index()
    {
       //$info = file_get_contents('http://wechat.18022480300.com/wechat/index');
       $access_token = $this->get_access_token();
       echo $access_token;
    }

    /**
     * 获取微信access_token
     */
    public function get_access_token()
    {
        $key = 'wechat_access_token';
        //判断缓存是否存在
        if(Cache::has($key)) {
            //取缓存
            $wechat_access_token = Cache::get($key);
        }else{
            //取不到，调接口，缓存
            $re = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_SECRET'));
            $result = json_decode($re,true);
            Cache::put($key,$result['access_token'],$result['expires_in']);
            $wechat_access_token = $result['access_token'];
        }
       return $wechat_access_token;
    }
}
