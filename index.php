<?php
require_once('connection.php');
require_once('define.php');
$controller = "Homecontroller";
$site_url = "http://localhost/Dropbox/testing/mayank/";
$root_path = dirname(__FILE__);
$action = "index";
$base_directry = basename($root_path);
$fullRequest = $_SERVER['REQUEST_URI'];
$arrQuerystring = explode($base_directry."/",$fullRequest);
if(isset($_GET['pg']) && $_GET['pg'] != ""){
    $arrQuerystring[1] = $_GET['pg'];
}
if(isset($arrQuerystring[1]) && $arrQuerystring[1] != ""){
    $expController = explode("/",$arrQuerystring[1]);
    if(isset($expController[0]) && $expController[0] != ""){
        $controller =  $expController[0]."controller";
        if(!isset($expController[1])){
            $action = "index";
        }else{
            $action = $expController[1];
        }
    }
}
include_once("controller/".$controller.".php");
$controller = new $controller();
if(isset($action) && $action != ""){
    $controller->$action();
}
else{
    $controller->invoke();
    
}
?>