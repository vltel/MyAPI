<?php
// set headers
header('Content-Type: application/json;');
// add includes
require_once 'db_cfg.php';
require_once 'DataBase.php';
require_once 'Router.php';
require_once 'MyAPI.php';
// make router
$route = new Router();
// find route
$findRoute = $route->route();
if(!$findRoute) {
//  route does not found
    $array = array('result' => 'error');
    echo json_encode($array);
    exit;
}
// prepare config
$config = array(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
// make database connection
$db  = new DataBase($config);
// make API
$api = new MyAPI($db, $route);
// call method
$array = $api->$findRoute();
// show output
echo json_encode($array);
