<?php

namespace News\Home\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ClassisController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->tag->prependTitle("栏目查询 - ");
        $this->persistent->parameters = null;
    }

    /**
     * Searches for classis
     */
    public function searchAction($id)
    {
        $classis_name = \News\Admin\Models\Classis::findFirstByid($id)->name;

        $this->tag->prependTitle($classis_name." - ");

        $this->view->classis_name = $classis_name;
        $this->view->classis_id = $id;

        $numberPage = 1;
        $article = \News\Admin\Models\Article::find("cid = $id");
        if (count($article) == 0) {
            $this->flash->notice("The search did not find any article");

            return $this->dispatcher->forward(array(
                "controller" => "classis",
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
