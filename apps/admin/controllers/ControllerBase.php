<?php

namespace News\Admin\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ControllerBase extends Controller
{
    public function initialize()
    {
        // 检测是否登陆
        if ( ! ($this->session->get('auth')['id'] && $this->session->get('auth')['user_name']) ) {
            $this->response->redirect("admin/login");
        }

        // 设置网站后台管理 顶部显示值 <title> </title>
        $this->tag->setTitle(\News\Admin\Models\App::findFirst()->name);

        // 设置视图模板
        $this->view->setTemplateAfter('navigate');
    }

}
