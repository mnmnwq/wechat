<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Tools;

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

    public function do_upload()
    {
        $req = $this->request->all();
        if(!$this->request->hasFile('rsource')){
            dd('没有文件');
        }
        $file_obj = $this->request->file('rsource');
        $file_ext = $file_obj->getClientOriginalExtension();
        $file_name = time().rand(1000,9999).'.'.$file_ext;
        $path = $this->request->file('rsource')->storeAs('wechat/'.$req['type'],$file_name);
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$this->tools->get_access_token().'&type='.$req['type'];
        $re = $this->tools->wechat_curl_file($url,storage_path('app/public/'.$path));
        $result = json_decode($re,1);
        dd($result);
    }
}
