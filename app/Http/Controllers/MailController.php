<?php

namespace App\Http\Controllers;

use App\Mail\OrderShipped;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send_email()
    {
        Mail::to('328435440@qq.com')->send(new OrderShipped());
    }
}
