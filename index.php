<?php

error_reporting(E_ALL | E_STRICT);ini_set('display_errors',1);
require "vendor/autoload.php";
require_once "conf.php";

blog\model\Model::init();

$session = new blog\apps\Session();

// show error when debug
if (!$GLOBALS["envProd"]) {
  ini_set("display_startup_errors", 1);
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
}

// get a securized instance of the url
$url = explode ( "/", filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW));
// remove first empty entry
$url = array_slice($url, 3);

// select if it's front side, admin side or auth side
switch ($url[0]) {
  case 'admin':
    $ctrl = new \blog\controller\Back($session);
    $template = "back";
    break;
  case 'auth':
    $ctrl = new \blog\controller\Auth($session);
    $template = "auth";
    break;
  default:
    $ctrl = new \blog\controller\Front();
    $template = "front";
    break;
}


function sendHeader($value) {
  header($value);
}

function sendExit($value) {
  exit($value);
}


$page = $ctrl->getPage($url);
print_r(htmlspecialchars_decode(\blog\view\View::makeHtml($page, $template)));
