<?php

namespace blog\apps;

class Session{

  private $data;
  public function __construct(){
    session_start();
    $this->data = $_SESSION;
  }

  public function get($key){
    return $this->data[$key];
  }

  public function set($key, $value){
    $this->data[$key] = $value;
    return true;
  }

  public function setFlash($key, $message) {
    $this->data['flash'][$key] = $message;
  }

  public function getFlash() {
    $flash = $this->data['flash'];
    if(!empty($flash)){
      unset($this->data['flash']);
      return $flash;
    }
  }

  public function delete($key) {
    unset($this->data[$key]);
  }
}
//
// $maSession = new Session();
//
//
// $maSession->set("jjj",12);
//
// var_dump($_SESSION);
//
// class Session {
//
//   static $instance;
//
//   static function getInstance(){
//     if(!self::$instance){
//       self::$instance = new Session();
//     }
//     return self::$instance;
//   }
//
//   public function __construct(){
//     session_start();
//   }
//
//   public function setFlash($key, $message){
//     $_SESSION['flash'][$key] = $message;
//   }
//
//   public function hasFlashes(){
//     return isset($_SESSION['flash']);
//   }
//
//   public function getFlashes(){
//     $flash = $_SESSION['flash'];
//     if(!empty($flash)){
//       unset($_SESSION['flash']);
//       return $flash;
//     }
//   }
//
//   public function write($key, $value){
//     $_SESSION[$key] = $value;
//   }
//
//   public function delete($key){
//     unset($_SESSION[$key]);
//   }
//
// }
