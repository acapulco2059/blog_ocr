<?php

namespace blog\apps;

class Session{

  protected $data;

  public function __construct(){
    session_start();
    $this->data = $_SESSION;
   }

  public function get($key){
    return $this->data[$key];
  }

  public function set($key, $value){
    $this->data[$key] = $value;
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
