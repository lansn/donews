<?php

namespace News\Admin\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AppController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
       return $this->dispatcher->forward(array(
            "controller" => "app",
            "action" => "edit"
        ));
    }

    /**
     * Edits a app
     *
     * @param string $id
     */
    public function editAction($id = 1)
    {
        $this->tag->prependTitle("网站基本设置 - ");

        if (!$this->request->isPost()) {

            $app = \News\Admin\Models\App::findFirstByid($id);
            if (!$app) {
                $this->flash->error("app was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "app",
                    "action" => "index"
                ));
            }

            $this->view->id = $app->id;

            $this->tag->setDefault("id", $app->id);
            $this->tag->setDefault("name", $app->name);
            $this->tag->setDefault("http", $app->http);
            $this->tag->setDefault("keywords", $app->keywords);
            $this->tag->setDefault("description", $app->description);
            $this->tag->setDefault("icp", $app->icp);
            $this->tag->setDefault("email", $app->email);
            $this->tag->setDefault("tel", $app->tel);
            $this->tag->setDefault("fax", $app->fax);
            $this->tag->setDefault("address", $app->address);
            
        }
    }

    /**
     * Saves a app edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "app",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $app = \News\Admin\Models\App::findFirstByid($id);
        if (!$app) {
            $this->flash->error("app does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "app",
                "action" => "index"
            ));
        }

        $app->name = $this->request->getPost("name");
        $app->http = $this->request->getPost("http");
        $app->keywords = $this->request->getPost("keywords");
        $app->description = $this->request->getPost("description");
        $app->icp = $this->request->getPost("icp");
        $app->email = $this->request->getPost("email", "email");
        $app->tel = $this->request->getPost("tel");
        $app->fax = $this->request->getPost("fax");
        $app->address = $this->request->getPost("address");
        

        if (!$app->save()) {

            foreach ($app->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "app",
                "action" => "edit",
                "params" => array($app->id)
            ));
        }

        $this->flash->success("网站设置更新成功！");

        return $this->dispatcher->forward(array(
            "controller" => "app",
            "action" => "index"
        ));

    }


}

