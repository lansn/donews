<?php

namespace News\Admin\Controllers;

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
    public function searchAction()
    {
        $this->tag->prependTitle("栏目列表 - ");

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "\\News\\Admin\\Models\\Classis", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "sort asc";

        $classis = \News\Admin\Models\Classis::find($parameters);
        if (count($classis) == 0) {
            $this->flash->notice("The search did not find any classis");

            return $this->dispatcher->forward(array(
                "controller" => "classis",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $classis,
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
        $this->tag->prependTitle("创建栏目 - ");
    }

    /**
     * Edits a classi
     *
     * @param string $id
     */
    public function editAction($id)
    {

        $this->tag->prependTitle("栏目修改 - ");

        if (!$this->request->isPost()) {

            $classi = \News\Admin\Models\Classis::findFirstByid($id);
            if (!$classi) {
                $this->flash->error("classi was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "classis",
                    "action" => "index"
                ));
            }

            $this->view->id = $classi->id;

            $this->tag->setDefault("id", $classi->id);
            $this->tag->setDefault("name", $classi->name);
            $this->tag->setDefault("keywords", $classi->keywords);
            $this->tag->setDefault("description", $classi->description);
            $this->tag->setDefault("sort", $classi->sort);
            
        }
    }

    /**
     * Creates a new classi
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "classis",
                "action" => "index"
            ));
        }

        $classi = new \News\Admin\Models\Classis();

        $classi->name = $this->request->getPost("name");
        $classi->keywords = $this->request->getPost("keywords");
        $classi->description = $this->request->getPost("description");
        $classi->sort = $this->request->getPost("sort");
        

        if (!$classi->save()) {
            foreach ($classi->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "classis",
                "action" => "new"
            ));
        }

        $this->flash->success("classi was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "classis",
            "action" => "index"
        ));

    }

    /**
     * Saves a classi edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "classis",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $classi = \News\Admin\Models\Classis::findFirstByid($id);
        if (!$classi) {
            $this->flash->error("classi does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "classis",
                "action" => "index"
            ));
        }

        $classi->name = $this->request->getPost("name");
        $classi->keywords = $this->request->getPost("keywords");
        $classi->description = $this->request->getPost("description");
        $classi->sort = $this->request->getPost("sort");
        

        if (!$classi->save()) {

            foreach ($classi->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "classis",
                "action" => "edit",
                "params" => array($classi->id)
            ));
        }

        $this->flash->success("classi was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "classis",
            "action" => "index"
        ));

    }

    /**
     * Deletes a classi
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $classi = \News\Admin\Models\Classis::findFirstByid($id);
        if (!$classi) {
            $this->flash->error("classi was not found");

            return $this->dispatcher->forward(array(
                "controller" => "classis",
                "action" => "index"
            ));
        }

        if (!$classi->delete()) {

            foreach ($classi->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "classis",
                "action" => "search"
            ));
        }

        $this->flash->success("classi was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "classis",
            "action" => "index"
        ));
    }

}
