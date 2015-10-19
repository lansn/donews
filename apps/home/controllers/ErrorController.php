<?php

namespace News\Home\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ErrorController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->tag->prependTitle("好像出现错误啦，请联系我们的技术人员！ - ");
        $this->persistent->parameters = null;
    }

    public function _404Action()
    {
        $this->tag->prependTitle("404 哎呀，您好像迷路啦！ - ");   
    }

}
