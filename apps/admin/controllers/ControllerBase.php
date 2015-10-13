<?php

namespace News\Admin\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function initialize()
    {
        // 设置网站后台管理 顶部显示值 <title> </title>
        $this->tag->setTitle(\News\Admin\Models\App::findFirst()->name);
        $this->view->setTemplateAfter('navigate');
    }

}
