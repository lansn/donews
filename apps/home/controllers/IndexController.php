<?php

namespace News\Home\Controllers;

use Phalcon\Paginator\Adapter\Model as Paginator;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $numberPage = 1;

        $article = \News\Admin\Models\Article::find();
        if (count($article) == 0) {
            $this->flash->notice("The search did not find any article");

            return $this->dispatcher->forward(array(
                "controller" => "article",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $article,
            "limit"=> 20,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

}

