<?php

namespace News\Home\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function initialize()
    {
        $this->tag->setTitle(\News\Admin\Models\App::findFirst()->name);
    }
}
