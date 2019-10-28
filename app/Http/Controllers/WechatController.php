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

    public function data()
    {
        $re = file_get_contents('http://wechat.18022480300.com/wechat/youjia');
        $result = json_decode($re,1);
        $last_day = date('Y-m-d',strtotime('-1 days')); //昨天的数据
        $last_data = Cache::get($last_day);
        $last_result = json_decode($last_data,1);
        echo "<pre>";
        foreach ($result['result'] as $k=>$v){
            if(($last_result['result'][$k]['92h'] != $v['92h']) || ($last_result['result'][$k]['95h'] != $v['95h'])){
                //数据不一致
                //创建模板消息
                print_r($v);
            }
        }
    }


    /**
     *
     */
    public function get_location()
    {
        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $appid = env('WECHAT_APPID');
        $_now_ = time();
        $rand_str = rand(1000,9999).'jssdk'.time();
        $jsapi_ticket = $this->tools->get_jsapi_ticket();
        $sign_str = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$rand_str.'&timestamp='.$_now_.'&url='.$url;
        $signature = sha1($sign_str);
       return view('Wechat.location',['signature'=>$signature,'appid'=>$appid,'time'=>$_now_,'rand_str'=>$rand_str]);
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


    public function youjia()
    {
        $str = '{"resultcode":"200","reason":"查询成功!","result":[{"city":"北京","b90":"-","b93":"6.62","b97":"7.04","b0":"6.28","92h":"6.62","95h":"7.04","98h":"8.02","0h":"6.28"},{"city":"上海","b90":"-","b93":"6.58","b97":"7.00","b0":"6.22","92h":"6.58","95h":"7.00","98h":"7.70","0h":"6.22"},{"city":"江苏","b90":"-","b93":"6.59","b97":"7.01","b0":"6.21","92h":"6.59","95h":"7.01","98h":"7.89","0h":"6.21"},{"city":"天津","b90":"-","b93":"6.61","b97":"6.98","b0":"6.24","92h":"6.61","95h":"6.98","98h":"7.90","0h":"6.24"},{"city":"重庆","b90":"-","b93":"6.69","b97":"7.07","b0":"6.32","92h":"6.69","95h":"7.07","98h":"7.96","0h":"6.32"},{"city":"江西","b90":"-","b93":"6.58","b97":"7.07","b0":"6.29","92h":"6.58","95h":"7.07","98h":"8.07","0h":"6.29"},{"city":"辽宁","b90":"-","b93":"6.59","b97":"7.03","b0":"6.16","92h":"6.59","95h":"7.03","98h":"7.65","0h":"6.16"},{"city":"安徽","b90":"-","b93":"6.58","b97":"7.06","b0":"6.28","92h":"6.58","95h":"7.06","98h":"7.89","0h":"6.28"},{"city":"内蒙古","b90":"-","b93":"6.56","b97":"7.00","b0":"6.13","92h":"6.56","95h":"7.00","98h":"7.68","0h":"6.13"},{"city":"福建","b90":"-","b93":"6.59","b97":"7.04","b0":"6.24","92h":"6.59","95h":"7.04","98h":"7.70","0h":"6.24"},{"city":"宁夏","b90":"-","b93":"6.53","b97":"6.90","b0":"6.14","92h":"6.53","95h":"6.90","98h":"7.90","0h":"6.14"},{"city":"甘肃","b90":"-","b93":"6.51","b97":"6.96","b0":"6.15","92h":"6.51","95h":"6.96","98h":"7.40","0h":"6.15"},{"city":"青海","b90":"-","b93":"6.57","b97":"7.04","b0":"6.18","92h":"6.57","95h":"7.04","98h":"0","0h":"6.18"},{"city":"广东","b90":"-","b93":"6.64","b97":"7.19","b0":"6.25","92h":"6.64","95h":"7.19","98h":"8.07","0h":"6.25"},{"city":"山东","b90":"-","b93":"6.60","b97":"7.08","b0":"6.24","92h":"6.60","95h":"7.08","98h":"7.80","0h":"6.24"},{"city":"广西","b90":"-","b93":"6.68","b97":"7.22","b0":"6.31","92h":"6.68","95h":"7.22","98h":"8.00","0h":"6.31"},{"city":"山西","b90":"-","b93":"6.58","b97":"7.10","b0":"6.30","92h":"6.58","95h":"7.10","98h":"7.80","0h":"6.30"},{"city":"贵州","b90":"-","b93":"6.74","b97":"7.13","b0":"6.35","92h":"6.74","95h":"7.13","98h":"8.03","0h":"6.35"},{"city":"陕西","b90":"-","b93":"6.51","b97":"6.88","b0":"6.15","92h":"6.51","95h":"6.88","98h":"7.68","0h":"6.15"},{"city":"海南","b90":"-","b93":"7.73","b97":"8.20","b0":"6.33","92h":"7.73","95h":"8.20","98h":"9.27","0h":"6.33"},{"city":"四川","b90":"-","b93":"6.65","b97":"7.17","b0":"6.34","92h":"6.65","95h":"7.17","98h":"7.80","0h":"6.34"},{"city":"河北","b90":"-","b93":"6.61","b97":"6.98","b0":"6.24","92h":"6.61","95h":"6.98","98h":"7.80","0h":"6.24"},{"city":"西藏","b90":"-","b93":"7.51","b97":"7.94","b0":"6.80","92h":"7.51","95h":"7.94","98h":"0","0h":"6.80"},{"city":"河南","b90":"-","b93":"6.62","b97":"7.07","b0":"6.23","92h":"6.62","95h":"7.07","98h":"7.72","0h":"6.23"},{"city":"新疆","b90":"-","b93":"6.51","b97":"7.00","b0":"6.13","92h":"6.51","95h":"7.00","98h":"7.82","0h":"6.13"},{"city":"黑龙江","b90":"-","b93":"6.55","b97":"6.95","b0":"6.02","92h":"6.55","95h":"6.95","98h":"7.93","0h":"6.02"},{"city":"吉林","b90":"-","b93":"6.58","b97":"7.10","b0":"6.17","92h":"6.58","95h":"7.10","98h":"7.73","0h":"6.17"},{"city":"云南","b90":"-","b93":"6.76","b97":"7.26","b0":"6.32","92h":"6.76","95h":"7.26","98h":"7.94","0h":"6.32"},{"city":"湖北","b90":"-","b93":"6.62","b97":"7.09","b0":"6.23","92h":"6.62","95h":"7.09","98h":"7.66","0h":"6.23"},{"city":"浙江","b90":"-","b93":"6.59","b97":"7.01","b0":"6.23","92h":"6.59","95h":"7.01","98h":"7.68","0h":"6.23"},{"city":"湖南","b90":"-","b93":"6.58","b97":"6.99","b0":"6.31","92h":"6.58","95h":"6.99","98h":"7.79","0h":"6.31"}],"error_code":0}';
        echo $str;
    }


}
