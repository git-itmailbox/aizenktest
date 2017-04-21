<?php

use vendor\core\Router;

$query =trim($_SERVER['QUERY_STRING'], '/');

define('WWW', __DIR__);
define('CORE', dirname(__DIR__).'vendor/core');
define('ROOT', dirname(__DIR__));
define('APP', dirname(__DIR__).'/app');
define('LAYOUT', 'default');

require '../vendor/libs/functions.php';


spl_autoload_register(function ($class){
    $file = ROOT . '/' . str_replace('\\','/',$class). '.php';
    if(is_file($file)){
        require_once $file;
    }
});

////переадресация с /pages в /main
//Router::add('^somepages/?(?P<action>[a-z-]+)?$', ['controller' => 'Main']);
//Router::add('^page/?(?P<action>[a-z-]+)/(?P<alias>[a-z-]+)$', ['controller' => 'Page']);
//Router::add('^page/(?P<alias>[a-z-]+)$', ['controller' => 'Page', 'action'=>'view']);
//Router::add('^page/test/(?P<params>.+)$', ['controller' => 'Page', 'action'=>'test']);

Router::add('^searchuser/?$', ['controller' => 'Main', 'action' => 'searchUser']);

//defaults routes
Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?/(?P<params>.+)$');
//Router::add('^(?P<controller>[a-z-]+)/?$', ['action' => 'index']);

Router::dispatch($query);


