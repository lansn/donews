<?php

namespace News\Admin\Controllers;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->tag->prependTitle("后台管理 - ");
    }

}

