<?php

namespace News\Home\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ArticleController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->tag->prependTitle("文章查询 - ");
        $this->persistent->parameters = null;
    }

    /**
     * Searches for article
     */
    public function searchAction()
    {
        $this->tag->prependTitle("查询结果 - ");

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "\\News\\Admin\\Models\\Article", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id desc";

        $article = \News\Admin\Models\Article::find($parameters);
        if (count($article) == 0) {
            $this->flash->notice("The search did not find any article");

            return $this->dispatcher->forward(array(
                "controller" => "article",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $article,
            "limit"=> 30,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Edits a article
     *
     * @param string $id
     */
    public function editAction($id)
    {
        $this->tag->prependTitle("文章修改 - ");

        if (!$this->request->isPost()) {

            $article = \News\Admin\Models\Article::findFirstByid($id);
            if (!$article) {
                $this->flash->error("article was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "article",
                    "action" => "index"
                ));
            }

            $this->view->id = $article->id;

            $this->tag->setDefault("id", $article->id);
            $this->tag->setDefault("cid", $article->cid);
            $this->tag->setDefault("title", $article->title);
            $this->tag->setDefault("author", $article->author);
            $this->tag->setDefault("origin", $article->origin);
            $this->tag->setDefault("keywords", $article->keywords);
            $this->tag->setDefault("content", $article->content);
            //$this->tag->setDefault("datetime", $article->datetime);
            
        }
    }



}