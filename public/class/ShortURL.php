<?php

Class shortURL {

  public static function getPrefixeBack() {
    if(isset($GLOBALS["prefixeBack"])){
      $data = $GLOBALS["prefixeBack"];
      return $data;
    }
  }

  public static function getPrefixeFront() {
    if(isset($GLOBALS["prefixeFront"])){
      $data = $GLOBALS["prefixeFront"];
      return $data;
    }
  }

  public static function getPrefixeAuth() {
    if(isset($GLOBALS["prefixeAuth"])){
      $data = $GLOBALS["prefixeAuth"];
      return $data;
    }
  }
}
