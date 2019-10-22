<?php

namespace App\Http\Controllers;

use App\Model\UserWechat;
use Illuminate\Http\Request;
use App\Tools\Tools;

class EventController extends Controller
{

    public $tools;
    public $request;
    public function __construct(Tools $tools,Request $request)
    {
        $this->tools = $tools;
        $this->request = $request;
    }

    /**
     * 接收微信消息
     */
    public function event()
    {
        $info = file_get_contents("php://input");
        file_put_contents(storage_path('logs/wechat/'.date('Y-m-d').'.log'),"<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n",FILE_APPEND);
        file_put_contents(storage_path('logs/wechat/'.date('Y-m-d').'.log'),$info,FILE_APPEND);
        $xml_obj = simplexml_load_string($info,'SimpleXMLElement',LIBXML_NOCDATA);
        $xml_arr = (array)$xml_obj;
        //关注操作
        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'subscribe'){
            $wechat_user = $this->tools->get_wechat_user($xml_arr['FromUserName']);
            $msg = '欢迎'.$wechat_user['nickname'].'同学'.'，感谢您的关注';
            echo "<xml><ToUserName><![CDATA[".$xml_arr['FromUserName']."]]></ToUserName><FromUserName><![CDATA[".$xml_arr['ToUserName']."]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[".$msg."]]></Content></xml>";
        }
        //签到
        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'CLICK' && $xml_arr['EventKey'] == 'sign'){
            //签到
            //判断是否签到
            $usere_wechat = UserWechat::where(['openid'=>$xml_arr['FromUserName']])->first();
            $today = date('Y-m-d',time()); //今天
            $last_day = date('Y-m-d',strtotime("-1 days")); //昨天
            if($usere_wechat->sign_day == $today){
                //已经签到
                $msg = '您已签到';
                echo "<xml><ToUserName><![CDATA[".$xml_arr['FromUserName']."]]></ToUserName><FromUserName><![CDATA[".$xml_arr['ToUserName']."]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[".$msg."]]></Content></xml>";
            }else{
                //根据签到次数加积分
                //连续签到
                if($usere_wechat->sign_day == $last_day){
                    //连续签到
                    $sign_num = $usere_wechat->sign_num + 1;
                    if($sign_num >= 6){
                        $sign_num = 1;
                    }
                    UserWechat::where(['openid'=>$xml_arr['FromUserName']])->update([
                        'sign_day'=>$today,
                        'sign_num'=>$sign_num,
                        'sign_score'=>$usere_wechat->sign_score + 5 * $sign_num
                    ]);
                }else{
                    //非连续签到
                    UserWechat::where(['openid'=>$xml_arr['FromUserName']])->update([
                        'sign_day'=>$today,
                        'sign_num'=>1,
                        'sign_score'=>$usere_wechat->sign_score + 5
                        ]);
                }

            }

        }
        //普通消息
        if($xml_arr['MsgType'] == 'text' && $xml_arr['Content'] == '1111'){
            //$msg = '你好！';
            $media_id = 'dcgUiQ4LgcdYRovlZqP88cOUX_tQ1JQGV3GJf3qzPKI';
            echo "<xml><ToUserName><![CDATA[".$xml_arr['FromUserName']."]]></ToUserName><FromUserName><![CDATA[".$xml_arr['ToUserName']."]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[voice]]></MsgType><Voice><MediaId><![CDATA[".$media_id."]]></MediaId></Voice></xml>";
            //echo "<xml><ToUserName><![CDATA[".$xml_arr['FromUserName']."]]></ToUserName><FromUserName><![CDATA[".$xml_arr['ToUserName']."]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[".$media_id."]]></MediaId></Image></xml>";
            //echo "<xml><ToUserName><![CDATA[".$xml_arr['FromUserName']."]]></ToUserName><FromUserName><![CDATA[".$xml_arr['ToUserName']."]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[".$msg."]]></Content></xml>";
        }
    }
}
