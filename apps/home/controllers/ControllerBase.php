<?php

namespace News\Home\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function initialize()
    {
        // 获取站点基本设置
        $app = \News\Admin\Models\App::findFirst();

        // 设置传递给视图使用
        $this->view->app = $app;

        // 设置标题
        $this->tag->setTitle($app->name);

        // 获取导航菜单
        $this->view->navigate = \News\Admin\Models\Classis::find(array('order' => 'sort asc'));

        // 当前所选分类 默认值
        $this->view->classis_id = 'index';

        // 获取广告轮播
        $this->view->carousel = \News\Admin\Models\Carousel::find();
    }

}
