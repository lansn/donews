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

        // 获取站点基本设置
        $app = \News\Admin\Models\App::findFirst();

        // 设置传递给视图使用
        $this->view->app = $app;

        // 设置标题
        $this->tag->setTitle($app->name);

        // 设置视图模板
        $this->view->setTemplateAfter('navigate');
    }

}
