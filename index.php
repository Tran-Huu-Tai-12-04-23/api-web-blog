<?php
    ob_start();
    header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
    header('content-type: application/json');

    // handle the request
    define('BASE_URL', "/API-WEB_BLOG/");
    // include './config/index.php';
    include 'Route/index.php';
    // include './utils/index.php';
    // include './middleware/index.php';
    // handle get request change route of application
    $url = isset($_GET['url']) ? $_GET['url'] : '' ;
    if(  substr($url, strlen($url)-1) === '/' ) {
        $url = substr($url,0, strlen($url)-1);
    }
    $action = isset($_GET['action']) ? $_GET['action'] : '' ;
    $appRoute = new Router();
    $appRoute->switchRequest($url, $action);
?>
