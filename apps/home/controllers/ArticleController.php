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

        $this->tag->prependTitle("第 ".$paginator->getPaginate()->current." 页 - ");
        
    }

    /**
     * Edits a article
     *
     * @param string $id
     */
    public function getAction($id)
    {
        $article = \News\Admin\Models\Article::findFirstByid($id);

        if (!$article) {
            $this->flash->error("article was not found");

            return $this->dispatcher->forward(array(
                "controller" => "error",
                "action" => "_404"
            ));
        }

        $this->tag->setDefault("id", $id);

        // 本 id 新闻
        $this->view->article = $article;

        $this->view->keywords = $article->keywords;
        //$this->view->description = $app->description;

        // 本 id 新闻之分类id
        $this->view->classis_id = $article->cid;

        // 最新 10 篇新闻（前10）
        $this->view->top_ten = \News\Admin\Models\Article::find(array('order' => 'id desc', 'limit' => 10));

        //上一篇 下一篇
        $this->view->prev = \News\Admin\Models\Article::findFirst(array("id < $id", 'order' => 'id desc'));
        $this->view->next = \News\Admin\Models\Article::findFirst(array("id > $id", 'order' => 'id asc'));

        // 评论
        $this->view->comment = \News\Admin\Models\Comment::find(array("aid = $id AND status = 0", "order" => "id desc"));

        $this->tag->prependTitle($article->title." - "); 
        
    }

    public function commentAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "article",
                "action" => "index"
            ));
        }

        $comment = new \News\Admin\Models\Comment();

        $comment->aid = $this->request->getPost("id");
        $comment->author = $this->request->getPost("author");
        $comment->content = $this->request->getPost("content");
        $comment->datetime = date('Y-m-d H:i:s');
        

        if (!$comment->save()) {
            foreach ($comment->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "article",
                "action" => "index"
            ));
        }

        $this->flash->success("评论提交成功，请等待管理员审核！");

        return $this->dispatcher->forward(array(
            "controller" => "article",
            "action" => "get",
            "params" => array( $this->request->getPost("id") )
        ));

    }

}
