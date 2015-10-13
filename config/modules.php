<?php

/**
 * 注册应用程序多模块（便于前、后台管理分离） Register application modules
 */
$application->registerModules(array(
    'home' => array(
        'className' => 'News\Home\Module',
        'path' => __DIR__ . '/../apps/home/Module.php'
    ),
    'admin' => array(
        'className' => 'News\Admin\Module',
        'path' => __DIR__ . '/../apps/admin/Module.php'
    )
));
