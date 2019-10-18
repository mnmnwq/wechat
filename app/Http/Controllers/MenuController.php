<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tools\Tools;
use App\Model\Resource;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{

    public $tools;
    public $request;
    public function __construct(Tools $tools,Request $request)
    {
        $this->tools = $tools;
        $this->request = $request;
    }
    public function menu_list()
    {
        $data = [
            'button'=>[
                [
                    'type'=>'click',
                    'name'=>'今日歌曲',
                    'key'=>'V1001_TODAY_MUSIC'
                ],
                [
                    'name'=>"菜单",
                    'sub_button'=>[
                        [
                            'type'=>'view',
                            'name'=>'搜索',
                            'url'=>'http://www.soso.com/'
                        ],
                        [
                            'type'=>'click',
                            'name'=>'赞一下我们',
                            'key'=>'V1001_GOOD'
                        ]
                    ]
                ]
            ],
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->tools->get_access_token();
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        dd($re);
    }
}
