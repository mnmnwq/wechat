<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Tools;

class TagController extends Controller
{
    public $tools;
    public $request;
    public function __construct(Tools $tools,Request $request)
    {
        $this->tools = $tools;
        $this->request = $request;
    }

    public function del_tag()
    {
        $req = $this->request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/delete?access_token='.$this->tools->get_access_token();
        $data = [
            'tag'=>[
                'id'=>$req['tag_id']
            ]
        ];
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($re,1);
        dd($result);

    }

    public function update_tag()
    {
        $req = $this->request->all();

        return view('Tag.updateTag',['tag_id'=>$req['tag_id'],'tag_name'=>$req['tag_name']]);
    }

    public function do_update_tag()
    {

        $req = $this->request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/update?access_token='.$this->tools->get_access_token();
        $data = [
            'tag'=>[
                'id'=>$req['tag_id'],
                'name'=>$req['tag_name']
            ]
        ];
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($re,1);
        dd($result);
    }

    public function add_tag()
    {
        return view('Tag.addTag');
    }

    public function do_add_tag()
    {
        $req = $this->request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$this->tools->get_access_token();
        $data = [
            'tag'=>[
                'name'=>$req['tag_name']
            ]
        ];
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($re,1);
        dd($result);
    }

    public function tag_list()
    {
        //公众号标签列表
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->tools->get_access_token();
        $re = $this->tools->curl_get($url);
        $result = json_decode($re,1);
        return view('Tag.tagList',['data'=>$result['tags']]);
    }
}
