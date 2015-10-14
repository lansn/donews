<?php

namespace News\Admin\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class LoginController extends Controller
{
    public function initialize()
    {
        //设置网站后台管理 顶部显示 <title> 值 </title>
        $this->tag->setTitle(\News\Admin\Models\App::findFirst()->name);
    }

    public function indexAction()
    {
        $this->tag->prependTitle("管理员登陆 - ");
    }

    public function authAction()
    {
        if ($this->request->isPost()) {

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = \News\Admin\Models\Admin::findFirst(array(
                "(email = :email: OR user_name = :email:) AND password = :password: ",
                'bind' => array('email' => $email, 'password' => sha1( md5($password) ))
            )); 
            if ($user != false) {

                 $this->session->set('auth', array(
                        'id' => $user->id,
                        'user_name' => $user->user_name,
                        'true_name' => $user->name,
                        'group' => $user->group
                    ));

                $this->response->redirect("admin/index");
            } else {
                $this->flash->error('Wrong email/password');
            }

        }

        return $this->dispatcher->forward(array(
                "controller" => "login",
                "action" => "index"
        ));
    }

    public function exitAction()
    {
        $this->session->destroy();
        
        return $this->dispatcher->forward(array(
            "controller" => "login",
            "action" => "index"
        ));  
    }
}

