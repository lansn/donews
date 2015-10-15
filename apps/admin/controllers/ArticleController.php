<?php

namespace News\Admin\Controllers;

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
        $this->tag->prependTitle("文章列表 - ");

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
     * Displays the creation form
     */
    public function newAction()
    {
        $this->tag->prependTitle("文章发布 - ");
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

    /**
     * Creates a new article
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "article",
                "action" => "index"
            ));
        }

        $article = new \News\Admin\Models\Article();

        //发布者id
        $article->uid = $this->session->get('auth')['id'];

        $article->cid = $this->request->getPost("cid");
        $article->title = $this->request->getPost("title");
        $article->author = $this->request->getPost("author");
        $article->origin = $this->request->getPost("origin");
        $article->keywords = $this->request->getPost("keywords");
        $article->content = $this->request->getPost("content");
        $article->datetime = date('Y-m-d H:i:s');
        

        if (!$article->save()) {
            foreach ($article->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "article",
                "action" => "new"
            ));
        }

        $this->flash->success("article was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "article",
            "action" => "index"
        ));

    }

    /**
     * Saves a article edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "article",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $article = \News\Admin\Models\Article::findFirstByid($id);
        if (!$article) {
            $this->flash->error("article does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "article",
                "action" => "index"
            ));
        }

        $article->cid = $this->request->getPost("cid");
        $article->title = $this->request->getPost("title");
        $article->author = $this->request->getPost("author");
        $article->origin = $this->request->getPost("origin");
        $article->keywords = $this->request->getPost("keywords");
        $article->content = $this->request->getPost("content");
        $article->datetime = date('Y-m-d H:i:s');
        

        if (!$article->save()) {

            foreach ($article->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "article",
                "action" => "edit",
                "params" => array($article->id)
            ));
        }

        $this->flash->success("article was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "article",
            "action" => "index"
        ));

    }

    /**
     * Deletes a article
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $article = \News\Admin\Models\Article::findFirstByid($id);
        if (!$article) {
            $this->flash->error("article was not found");

            return $this->dispatcher->forward(array(
                "controller" => "article",
                "action" => "index"
            ));
        }

        if (!$article->delete()) {

            foreach ($article->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "article",
                "action" => "search"
            ));
        }

        $this->flash->success("article was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "article",
            "action" => "index"
        ));
    }

}
