<?php

namespace blog\apps;

class Session{

  public $data;

  public function __construct(){
    session_start();
    $this->data = $_SESSION;
   }

  public function get($key){
    if(isset($_SESSION[$key]))
      return $_SESSION[$key];
    else {
      return null;
    }
  }

  public function set($key, $value){
    $_SESSION[$key] = $value;
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
    unset($_SESSION[$key]);
  }
}
