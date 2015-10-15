<?php

namespace News\Admin\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class CommentController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->tag->prependTitle("评论查询 - ");
        $this->persistent->parameters = null;
    }

    /**
     * Searches for comment
     */
    public function searchAction()
    {

        $this->tag->prependTitle("评论列表 - ");

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "\\News\\Admin\\Models\\Comment", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id desc";

        $comment = \News\Admin\Models\Comment::find($parameters);
        if (count($comment) == 0) {
            $this->flash->notice("The search did not find any comment");

            return $this->dispatcher->forward(array(
                "controller" => "comment",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $comment,
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
        $this->tag->prependTitle("发布评论 - ");
    }

    /**
     * Edits a comment
     *
     * @param string $id
     */
    public function editAction($id)
    {

        $this->tag->prependTitle("评论修改 - ");

        if (!$this->request->isPost()) {

            $comment = \News\Admin\Models\Comment::findFirstByid($id);
            if (!$comment) {
                $this->flash->error("comment was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "comment",
                    "action" => "index"
                ));
            }

            $this->view->id = $comment->id;

            $this->tag->setDefault("id", $comment->id);
            $this->tag->setDefault("aid", $comment->aid);
            $this->tag->setDefault("author", $comment->author);
            $this->tag->setDefault("datetime", $comment->datetime);
            $this->tag->setDefault("status", $comment->status);
            
        }
    }

    /**
     * Creates a new comment
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "comment",
                "action" => "index"
            ));
        }

        $comment = new \News\Admin\Models\Comment();

        $comment->aid = $this->request->getPost("aid");
        $comment->author = $this->request->getPost("author");
        $comment->datetime = $this->request->getPost("datetime");
        $comment->status = $this->request->getPost("status");
        

        if (!$comment->save()) {
            foreach ($comment->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "comment",
                "action" => "new"
            ));
        }

        $this->flash->success("comment was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "comment",
            "action" => "index"
        ));

    }

    /**
     * Saves a comment edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "comment",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $comment = \News\Admin\Models\Comment::findFirstByid($id);
        if (!$comment) {
            $this->flash->error("comment does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "comment",
                "action" => "index"
            ));
        }

        $comment->aid = $this->request->getPost("aid");
        $comment->author = $this->request->getPost("author");
        $comment->datetime = $this->request->getPost("datetime");
        $comment->status = $this->request->getPost("status");
        

        if (!$comment->save()) {

            foreach ($comment->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "comment",
                "action" => "edit",
                "params" => array($comment->id)
            ));
        }

        $this->flash->success("评论更新成功！");

        return $this->dispatcher->forward(array(
            "controller" => "comment",
            "action" => "index"
        ));

    }

   /**
     * Approves a comment
     *
     * @param string $id
     */
    public function approveAction($id)
    {

        $comment = \News\Admin\Models\Comment::findFirstByid($id);
        if (!$comment) {
            $this->flash->error("comment was not found");

            return $this->dispatcher->forward(array(
                "controller" => "comment",
                "action" => "index"
            ));
        }

        $comment->status = 0;

        if (!$comment->save()) {

            foreach ($comment->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "comment",
                "action" => "search"
            ));
        }

        $this->flash->success("评论审核成功！");

        return $this->dispatcher->forward(array(
            "controller" => "comment",
            "action" => "index"
        ));
    }

    /**
     * Deletes a comment
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $comment = \News\Admin\Models\Comment::findFirstByid($id);
        if (!$comment) {
            $this->flash->error("comment was not found");

            return $this->dispatcher->forward(array(
                "controller" => "comment",
                "action" => "index"
            ));
        }

        if (!$comment->delete()) {

            foreach ($comment->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "comment",
                "action" => "search"
            ));
        }

        $this->flash->success("评论删除成功！");

        return $this->dispatcher->forward(array(
            "controller" => "comment",
            "action" => "index"
        ));
    }

}
