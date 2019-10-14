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

    public function push_tag_msg()
    {
        $req = $this->request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->tools->get_access_token();
        $data = [
            'filter'=> [
                "is_to_all"=>false,
                "tag_id"=>$req['tag_id']
            ],
            'text'=>[
                'content'=>'1111111111111111'
            ],
            'msgtype'=>'text'
        ];
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($re,1);
        dd($result);
    }

    /**
     * 用户身上标签
     */
    public function user_tag()
    {
        $req = $this->request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token='.$this->tools->get_access_token();
        $data = [
            'openid'=>$req['openid']
        ];
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($re,1);
        $tag_list = $this->tools->tag_list()['tags'];
        foreach($result['tagid_list'] as $v){
            foreach($tag_list as $vo){
                if($v == $vo['id']){
                    echo $vo['name']."<br>";
                }
            }
        }
    }

    public function add_user_tag()
    {
        $req = $this->request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token='.$this->tools->get_access_token();
        $data = [
            'tagid'=>$req['tag_id'],
            'openid_list'=>$req['opneid_list']
        ];
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($re,1);
        dd($result);
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
        $result = $this->tools->tag_list();
        return view('Tag.tagList',['data'=>$result['tags']]);
    }
}
