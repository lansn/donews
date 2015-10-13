<?php

use Phalcon\Mvc\Application;

error_reporting(E_ALL);

define('APP_PATH', realpath('..'));

try {

    /**
     * 载入配置 Read the configuration
     */
    $config = include APP_PATH . "/config/config.php";

    /**
     * 载入服务 Include services
     */
    require __DIR__ . '/../config/services.php';

    /**
     * 注入请求 Handle the request
     */
    $application = new Application($di);

    /**
     * 载入模块 Include modules
     */
    require __DIR__ . '/../config/modules.php';

    /**
     * 载入路由 Include routes
     */
    require __DIR__ . '/../config/routes.php';

    echo $application->handle()->getContent();

} catch (Exception $e) {
    echo $e->getMessage();
}

