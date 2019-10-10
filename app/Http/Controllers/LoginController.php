<?php

namespace App\Http\Controllers;

use App\Model\UserWechat;
use Illuminate\Http\Request;
use App\Model\User;
use DB;

class LoginController extends Controller
{
    public function wechat_login()
    {
        $redirect_uri = urlencode(env('APP_URL').'/wechat/code');
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
        //跳转
        header('Location:'.$url);
    }

    /**
     * 获取code并且换取access_token
     * @param Request $request
     */
    public function wechat_code(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_SECRET').'&code='.$req['code'].'&grant_type=authorization_code';
        $re = file_get_contents($url);
        $result = json_decode($re,1);
        $user_wechat = UserWechat::where(['openid'=>$result['openid']])->first();
        if(!empty($user_wechat)){
            //登陆
            $request->session()->put('uid',$user_wechat->uid);
        }else{
            // 注册
            DB::connection('mysql')->beginTransaction();
            $uid= User::insertGetId([
                'name'=>rand(1000,9999).time(),
                'password'=>'',
                'reg_time'=>time()
            ]);
            $insert_wechat = UserWechat::insert([
                'uid'=>$uid,
                'openid'=>$result['openid']
            ]);
            if($uid && $insert_wechat){
                //登陆
                $request->session()->put('uid',$uid);
                DB::connection('mysql')->commit();
            }else{
                dd('添加信息错误');
            }
        }
        return redirect('/');

    }
}
