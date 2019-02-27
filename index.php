<?php

require_once "conf.php";
require_once "view/View.php";

// show error when debug
if (!$GLOBALS["envProd"]) {
  ini_set("display_startup_errors", 1);
  ini_set('display_errors', 1); 
  error_reporting(E_ALL); 
}

// get a securized instance of the url
$url = explode ( "/", filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW));
// remove first empty entry 
$url = array_slice($url, 1);

// select if it's front side or admin side
switch ($url[0]) {
  case 'admin':
    require_once "controller/Back.php";
    $ctrl = new Back();
    $template = "back";
    break;
  default:
    require_once "controller/Front.php";
    $ctrl = new Front();
    $template = "front";
    break;
}

$page = $ctrl->getPage($url);
$view = new View();
echo $view->makeHtml($page, $template);