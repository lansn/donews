<?php

namespace News\Admin\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AdminController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->tag->prependTitle("管理员账户管理 - ");
        $this->persistent->parameters = null;
        
        return $this->dispatcher->forward(array(
            "controller" => "admin",
            "action" => "search"
        ));
    }

    /**
     * Searches for admin
     */
    public function searchAction()
    {
        $this->tag->prependTitle("管理员账户列表 - ");

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "\\News\\Admin\\Models\\Admin", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $admin = \News\Admin\Models\Admin::find($parameters);
        if (count($admin) == 0) {
            $this->flash->notice("没有查找到此账号！");

            return $this->dispatcher->forward(array(
                "controller" => "admin",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $admin,
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
        $this->tag->prependTitle("创建管理员账户 - ");
    }

    /**
     * Edits a admin
     *
     * @param string $id
     */
    public function editAction($id)
    {
        $this->tag->prependTitle("管理员账户信息修改 - ");

        if (!$this->request->isPost()) {

            $admin = \News\Admin\Models\Admin::findFirstByid($id);
            if (!$admin) {
                $this->flash->error("此账号不存在！");

                return $this->dispatcher->forward(array(
                    "controller" => "admin",
                    "action" => "index"
                ));
            }

            if ($this->session->get('auth')['id'] != $id && $this->session->get('auth')['group'] != 0) {
                $this->flash->error("您无权限修改！");

                return $this->dispatcher->forward(array(
                    "controller" => "admin",
                    "action" => "index"
                ));
            }

            $this->view->id = $admin->id;

            $this->tag->setDefault("id", $admin->id);
            $this->tag->setDefault("user_name", $admin->user_name);
            $this->tag->setDefault("name", $admin->name);
            $this->tag->setDefault("password", '');
            $this->tag->setDefault("email", $admin->email);
            $this->tag->setDefault("group", $admin->group);
            
        }
    }

    /**
     * Creates a new admin
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "admin",
                "action" => "index"
            ));
        }

        $admin = new \News\Admin\Models\Admin();

        $admin->user_name = $this->request->getPost("user_name");
        $admin->name = $this->request->getPost("name");

        $password = $this->request->getPost("password");
        $admin->password = sha1( md5($password) );

        $admin->email = $this->request->getPost("email", "email");
        $admin->group = $this->request->getPost("group");       

        if (!$admin->save()) {
            foreach ($admin->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "admin",
                "action" => "new"
            ));
        }

        $this->flash->success("账号创建成功！");

        return $this->dispatcher->forward(array(
            "controller" => "admin",
            "action" => "index"
        ));

    }

    /**
     * Saves a admin edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "admin",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $admin = \News\Admin\Models\Admin::findFirstByid($id);
        if (!$admin) {
            $this->flash->error("此账号不存在！ " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "admin",
                "action" => "index"
            ));
        }

        $admin->user_name = $this->request->getPost("user_name");
        $admin->name = $this->request->getPost("name");

        $password = $this->request->getPost("password");
        if ( !empty($password) && $this->session->get('auth')['id'] == $id) {
            $admin->password = sha1( md5($password) );
        }
        
        $admin->email = $this->request->getPost("email", "email");

        if ( $this->session->get('auth')['group'] == 0) {
            $admin->group = $this->request->getPost("group");
        }

        if (!$admin->save()) {

            foreach ($admin->getMessages() as $message) {
                $this->flash->error($message);
            }
        }

        $this->flash->success("账户更新成功！");

        return $this->dispatcher->forward(array(
            "controller" => "admin",
            "action" => "edit",
            "params" => array($admin->id)
        ));

    }

    /**
     * Deletes a admin
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $admin = \News\Admin\Models\Admin::findFirstByid($id);
        if (!$admin) {
            $this->flash->error("此账号不存在！");

            return $this->dispatcher->forward(array(
                "controller" => "admin",
                "action" => "index"
            ));
        }

        if ( $this->session->get('auth')['group'] != 0) {
            $this->flash->error("您无权限删除！");

            return $this->dispatcher->forward(array(
                "controller" => "admin",
                "action" => "index"
            ));
        }

        if (!$admin->delete()) {

            foreach ($admin->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "admin",
                "action" => "search"
            ));
        }

        $this->flash->success("账号删除成功！");

        return $this->dispatcher->forward(array(
            "controller" => "admin",
            "action" => "index"
        ));
    }
}

