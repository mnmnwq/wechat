<?php

namespace App\Http\Controllers;

use App\Model\Resource;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Tools\Tools;
use Illuminate\Support\Facades\Storage;

class WechatController extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function wechat_list()
    {
        $user_info = User::get();
        return view('Wechat.wechatList',['user_info'=>$user_info]);
    }

    public function create_qrcode(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->tools->get_access_token();
        //{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
        $data = [
            'expire_seconds'=> 30 * 24 * 3600,
            'action_name'=>'QR_SCENE',
            'action_info'=>[
                'scene'=>[
                    'scene_id'=>$req['uid']
                ]
            ]
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        $qrcode_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$result['ticket'];
        $qrcode_source = $this->tools->curl_get($qrcode_url);
        $qrcode_name = $req['uid'].rand(10000,99999).'.jpg';
        Storage::put('wechat/qrcode/'.$qrcode_name, $qrcode_source);
        User::where(['id'=>$req['uid']])->update([
            'qrcode_url'=>'/storage/wechat/qrcode/'.$qrcode_name
        ]);
        return redirect('/wechat/wechat_list');
    }

    public function push_template_msg()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_access_token();
        $data = [
            'touser'=>'otAUQ1cBSc0kvc5sIFtI3DR4T3pQ',
            'template_id'=>'U-AJXr3Od395eeOSEkSNbn1uvOpPIT5ULVJ-wdw3qis',
            'data'=>[
                'keyword1'=>[
                    'value'=>'用户',
                    'color'=>''
                ],
                'keyword2'=>[
                    'value'=>'洗发水',
                    'color'=>''
                ]
            ]
        ];
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($re,1);
        dd($result);
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
