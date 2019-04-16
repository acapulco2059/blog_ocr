<?php

error_reporting(E_ALL | E_STRICT);ini_set('display_errors',1);
require_once "conf.php";
Model::init();

spl_autoload_register(function ($class_name) {
    include 'public/class/' . $class_name . '.php';
});

$session = new Session();

// show error when debug
// if (!$GLOBALS["envProd"]) {
//   ini_set("display_startup_errors", 1);
//   ini_set('display_errors', 1);
//   error_reporting(E_ALL);
// }

// get a securized instance of the url
$url = explode ( "/", filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW));
// remove first empty entry
$url = array_slice($url, 3);

// select if it's front side or admin side
switch ($url[0]) {
  case 'admin':
  require_once "controller/Back.php";
    $ctrl = new Back($session);
    $template = "back";
    break;
  case 'auth':
  require_once "controller/Auth.php";
    $ctrl = new Auth($session);
    $template = "auth";
    break;
  default:
    require_once "controller/Front.php";
    $ctrl = new Front();
    $template = "front";
    break;
}

$page = $ctrl->getPage($url);
print_r(htmlspecialchars_decode(View::makeHtml($page, $template)));
