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
        echo $_GET['echostr'];
    }
}
