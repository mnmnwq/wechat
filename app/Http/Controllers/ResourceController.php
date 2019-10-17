<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Tools;
use App\Model\Resource;

class ResourceController extends Controller
{
    public $tools;
    public $request;
    public function __construct(Tools $tools,Request $request)
    {
        $this->tools = $tools;
        $this->request = $request;
    }
    public function upload()
    {
        return view('Resource.upload');
    }

    /**
     * 资源列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resource_list()
    {
        $req = $this->request->all();
        !isset($req['type']) ? $type = 1 : $type = $req['type'];
        $info = Resource::where(['type'=>$type])->paginate(10);
        return view('Resource.resourceList',['info'=>$info,'type'=>$type]);
    }

    public function source_list()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->tools->get_access_token();
        $data = [
            'type'=>'image',
            'offset'=>'0',
            'count'=>20
        ];
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($re,1);
        dd($result);
    }

    public function do_upload()
    {
        $req = $this->request->all();
        $type_arr = ['image'=>1,'voice'=>2,'video'=>3,'thumb'=>4];
        if(!$this->request->hasFile('rsource')){
            dd('没有文件');
        }
        $file_obj = $this->request->file('rsource');
        $file_ext = $file_obj->getClientOriginalExtension();
        $file_name = time().rand(1000,9999).'.'.$file_ext;
        $path = $this->request->file('rsource')->storeAs('wechat/'.$req['type'],$file_name);
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->tools->get_access_token().'&type='.$req['type'];
        $data = [
            'media'=>new \CURLFile(storage_path('app/public/'.$path)),
        ];
        if($req['type'] == 'video'){
            $data['description'] = [
                'title'=>'标题',
                'introduction'=>'描述'
            ];
        }
        $re = $this->tools->wechat_curl_file($url,$data);
        $result = json_decode($re,1);
        if(!isset($result['errcode'])){
            Resource::insert([
                'media_id'=>$result['media_id'],
                'type'=>$type_arr[$req['type']],
                'path'=>'/storage/'.$path,
                'addtime'=>time()
            ]);
        }
    }
}
