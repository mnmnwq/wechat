<?php

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use App\Tools\Tools;
use Illuminate\Http\Request;


class TagController extends AdminController
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }
    public function add_tag()
    {
        return view();
    }

    public function del_tag(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/delete?access_token='.$this->tools->get_access_token();
        $data = [
            'tag'=>[
                'id'=>$req['tag_id']
            ]
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        if($result['errcode'] == 0){
            return redirect('admin/wechat/tag');
        }
    }
    public function tag_list()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->tools->get_access_token();
        $re = $this->tools->curl_get($url);
        $result = json_decode($re,1);
        $headers = ['标签ID','标签名称', '操作'];
        $rows = [];
        foreach($result['tags'] as $v){
            $row = [];
            $row[] = $v['id'];
            $row[] = $v['name'];
            $row[] = '<a href="'.env('APP_URL').'">修改</a> | <a href="'.env('APP_URL').'/admin/wechat/del_tag?tag_id='.$v['id'].'">删除</a>';
            $rows[] = $row;
        }
        /*$rows = [
            [1, 'labore21@yahoo.com', 'Ms. Clotilde Gibson', '<a href="http://www.wechat.com/admin/wechat/add_tag">aaaaaaaa</a>'],
            [2, 'omnis.in@hotmail.com', 'Allie Kuhic', 'Murphy, Koepp and Morar'],
            [3, 'quia65@hotmail.com', 'Prof. Drew Heller', 'Kihn LLC'],
            [4, 'xet@yahoo.com', 'William Koss', 'Becker-Raynor'],
            [5, 'ipsa.aut@gmail.com', 'Ms. Antonietta Kozey Jr.'],
        ];*/

        $table = new Table($headers, $rows);

        echo $table->render();
    }
}