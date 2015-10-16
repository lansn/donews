<?php

namespace News\Admin\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class CarouselController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->tag->prependTitle("广告轮播查询 - ");
        $this->persistent->parameters = null;
    }

    /**
     * Searches for carousel
     */
    public function searchAction()
    {
        $this->tag->prependTitle("广告轮播列表 - ");

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "\\News\\Admin\\Models\\Carousel", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $carousel = \News\Admin\Models\Carousel::find($parameters);
        if (count($carousel) == 0) {
            $this->flash->notice("The search did not find any carousel");

            return $this->dispatcher->forward(array(
                "controller" => "carousel",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $carousel,
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
        $this->tag->prependTitle("创建广告轮播 - ");
    }

    /**
     * Edits a carousel
     *
     * @param string $id
     */
    public function editAction($id)
    {
        $this->tag->prependTitle("广告轮播修改 - ");

        if (!$this->request->isPost()) {

            $carousel = \News\Admin\Models\Carousel::findFirstByid($id);
            if (!$carousel) {
                $this->flash->error("carousel was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "carousel",
                    "action" => "index"
                ));
            }

            $this->view->id = $carousel->id;

            $this->tag->setDefault("id", $carousel->id);
            $this->tag->setDefault("title", $carousel->title);
            $this->tag->setDefault("url", $carousel->url);
            $this->tag->setDefault("image", $carousel->image);
            $this->tag->setDefault("description", $carousel->description);
            $this->tag->setDefault("active", $carousel->active);
            
        }
    }

    /**
     * Creates a new carousel
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "carousel",
                "action" => "index"
            ));
        }

        $carousel = new \News\Admin\Models\Carousel();

        $carousel->title = $this->request->getPost("title");
        $carousel->url = $this->request->getPost("url");
        $carousel->image = $this->request->getPost("image");
        $carousel->description = $this->request->getPost("description");
        $carousel->active = $this->request->getPost("active");
        

        if (!$carousel->save()) {
            foreach ($carousel->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "carousel",
                "action" => "new"
            ));
        }

        $this->flash->success("carousel was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "carousel",
            "action" => "index"
        ));

    }

    /**
     * Saves a carousel edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "carousel",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $carousel = \News\Admin\Models\Carousel::findFirstByid($id);
        if (!$carousel) {
            $this->flash->error("carousel does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "carousel",
                "action" => "index"
            ));
        }

        $carousel->title = $this->request->getPost("title");
        $carousel->url = $this->request->getPost("url");
        $carousel->image = $this->request->getPost("image");
        $carousel->description = $this->request->getPost("description");
        $carousel->active = $this->request->getPost("active");
        

        if (!$carousel->save()) {

            foreach ($carousel->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "carousel",
                "action" => "edit",
                "params" => array($carousel->id)
            ));
        }

        $this->flash->success("carousel was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "carousel",
            "action" => "index"
        ));

    }

    /**
     * Deletes a carousel
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $carousel = \News\Admin\Models\Carousel::findFirstByid($id);
        if (!$carousel) {
            $this->flash->error("carousel was not found");

            return $this->dispatcher->forward(array(
                "controller" => "carousel",
                "action" => "index"
            ));
        }

        if (!$carousel->delete()) {

            foreach ($carousel->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "carousel",
                "action" => "search"
            ));
        }

        $this->flash->success("carousel was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "carousel",
            "action" => "index"
        ));
    }

}
