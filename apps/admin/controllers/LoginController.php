<?php

namespace News\Admin\Controllers;

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function initialize()
    {
        //设置网站后台管理 顶部显示 <title> 值 </title>
        $this->tag->setTitle(\News\Admin\Models\App::findFirst()->name);
    }

    public function indexAction()
    {
        $this->tag->prependTitle("登陆 - ");
    }

    public function authAction()
    {
        
    }

    public function exitAction()
    {

       return $this->dispatcher->forward(array(
            "controller" => "login",
            "action" => "index"
        ));  
    }
}

