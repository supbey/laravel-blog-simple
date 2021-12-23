<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{

    /*
    public function sendMail(){
        \Mail::send('email.test',['name'=>$name],function($message){
            $to = '214800358@qq.com';
            $message ->to($to)->subject('测试邮件');
        });
    }
    */

    public function sendMail(){
        // Mail::to('214800358@qq.com')->send(new TestMail());
    }

}


