<?php
session_start();

$controllerName = null;
$methodName = null;
if (!empty($_REQUEST['c'])) {
    $controllerName = $_REQUEST['c'] . 'Controller';

    if (!empty($_REQUEST['m'])) {
        $methodName = $_REQUEST['m'];
    }

} else {
    $controllerName = 'IndexController';
    $methodName = 'index';
}

$controllerFileName = __DIR__ . '/controller/' . $controllerName . '.php';

if (!file_exists($controllerFileName)) {
    echo '404 - Page Not Found';
    return;
}

require_once $controllerFileName;

if (!class_exists($controllerName)) {
    echo '404 - Page Not Found';
    return;
}

$controller = new $controllerName;

$uri = $_SERVER['QUERY_STRING'];
$params = [];
parse_str($uri, $params);

unset($params['c']);
unset($params['m']);

if (is_callable([$controller, $methodName])) {
    call_user_func_array([$controller, $methodName], $params);
//    echo $controller->$methodName($_REQUEST['a']);
    return;
} else {
    echo '404 - Page Not Found';
    return;
}


?>
