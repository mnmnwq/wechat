<?php

namespace App\Http\Controllers;

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
        file_put_contents(storage_path('logs/wechat/'.date('Y-m-d').'.log'),$info,FILE_APPEND);
    }
}
