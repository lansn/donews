<?php

namespace News\Admin\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class FriendlyLinkController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->tag->prependTitle("友情链接查询 - ");
        $this->persistent->parameters = null;
    }

    /**
     * Searches for friendly_link
     */
    public function searchAction()
    {
        $this->tag->prependTitle("友情链接列表 - ");

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "\\News\\Admin\\Models\\FriendlyLink", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id desc";

        $friendly_link = \News\Admin\Models\FriendlyLink::find($parameters);
        if (count($friendly_link) == 0) {
            $this->flash->notice("The search did not find any friendly_link");

            return $this->dispatcher->forward(array(
                "controller" => "friendly_link",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $friendly_link,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        $this->tag->prependTitle("添加友情链接 - ");
        $this->tag->setDefault("status", 1);
    }

    /**
     * Edits a friendly_link
     *
     * @param string $id
     */
    public function editAction($id)
    {
        $this->tag->prependTitle("友情链接修改 - ");

        if (!$this->request->isPost()) {

            $friendly_link = \News\Admin\Models\FriendlyLink::findFirstByid($id);
            if (!$friendly_link) {
                $this->flash->error("friendly_link was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "friendly_link",
                    "action" => "index"
                ));
            }

            $this->view->id = $friendly_link->id;

            $this->tag->setDefault("id", $friendly_link->id);
            $this->tag->setDefault("name", $friendly_link->name);
            $this->tag->setDefault("url", $friendly_link->url);
            $this->tag->setDefault("description", $friendly_link->description);
            $this->tag->setDefault("status", $friendly_link->status);
            
        }
    }

    /**
     * Creates a new friendly_link
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "friendly_link",
                "action" => "index"
            ));
        }

        $friendly_link = new \News\Admin\Models\FriendlyLink();

        $friendly_link->name = $this->request->getPost("name");
        $friendly_link->url = $this->request->getPost("url");
        $friendly_link->description = $this->request->getPost("description");
        $friendly_link->status = $this->request->getPost("status");
        

        if (!$friendly_link->save()) {
            foreach ($friendly_link->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "friendly_link",
                "action" => "new"
            ));
        }

        $this->flash->success("友情链接添加成功！");

        return $this->dispatcher->forward(array(
            "controller" => "friendly_link",
            "action" => "index"
        ));

    }

    /**
     * Saves a friendly_link edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "friendly_link",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $friendly_link = \News\Admin\Models\FriendlyLink::findFirstByid($id);
        if (!$friendly_link) {
            $this->flash->error("friendly_link does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "friendly_link",
                "action" => "index"
            ));
        }

        $friendly_link->name = $this->request->getPost("name");
        $friendly_link->url = $this->request->getPost("url");
        $friendly_link->description = $this->request->getPost("description");
        $friendly_link->status = $this->request->getPost("status");
        

        if (!$friendly_link->save()) {

            foreach ($friendly_link->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "friendly_link",
                "action" => "edit",
                "params" => array($friendly_link->id)
            ));
        }

        $this->flash->success("友情链接更新成功！");

        return $this->dispatcher->forward(array(
            "controller" => "friendly_link",
            "action" => "index"
        ));

    }

   /**
     * Approves a friendly_link
     *
     * @param string $id
     */
    public function approveAction($id)
    {

        $friendly_link = \News\Admin\Models\FriendlyLink::findFirstByid($id);
        if (!$friendly_link) {
            $this->flash->error("friendly_link was not found");

            return $this->dispatcher->forward(array(
                "controller" => "friendly_link",
                "action" => "index"
            ));
        }

        $friendly_link->status = 0;

        if (!$friendly_link->save()) {

            foreach ($friendly_link->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "friendly_link",
                "action" => "search"
            ));
        }

        $this->flash->success("友情链接审核成功！");

        return $this->dispatcher->forward(array(
            "controller" => "friendly_link",
            "action" => "index"
        ));
    }

    /**
     * Deletes a friendly_link
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $friendly_link = \News\Admin\Models\FriendlyLink::findFirstByid($id);
        if (!$friendly_link) {
            $this->flash->error("friendly_link was not found");

            return $this->dispatcher->forward(array(
                "controller" => "friendly_link",
                "action" => "search"
            ));
        }

        if (!$friendly_link->delete()) {

            foreach ($friendly_link->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "friendly_link",
                "action" => "search"
            ));
        }

        $this->flash->success("友情链接删除成功！");

        return $this->dispatcher->forward(array(
            "controller" => "friendly_link",
            "action" => "search"
        ));
    }

}
