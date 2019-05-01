<?php

namespace blog\controller;

class Auth {

  private $session;
  private $user;

  public function __construct($session = NULL){
    $this->session = $session;
    $this->user = new \blog\controller\UserManager();
  }


  public function getPage($url){
    $this->url = $url;
    $todo = $url[1];                                        //la fonction à appeler par défaut est le premier segment
    if ($todo == "") $todo = "home";                        //si il n'est pas défini on affiche la page d'accueil
    if ( !method_exists ( $this, $todo ) ) $todo = "home";  //si la fonction n'existe pas on affiche la page d'accueil
    return $this->$todo();
  }


  private function home(){
    global $prefixeFront;
    // $errorMess = $this->session->getFlashes();
    return [
      "{{ urlFront }}" => $prefixeFront,
      "{{ pageTitle }}" => 'Connexion'
    ];
  }


  public function connect($user){
    $this->session->set('auth', $user['data']);
  }

  public function login(){
    global $prefixeBack;
    global $prefixeAuth;
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $value = [
      "username" => $username
    ];

    if(!empty($username) && !empty($password)) {

      $user = $this->user->verify($value);
      if(password_verify($password, $user["data"]["password"])){
        $this->connect($user);
        sendExit(sendHeader("Location: ".$prefixeBack));
      }
      $this->session->setFlash("danger", "Mot de passe ou identifiant incorrect");
      sendHeader("location: ".$prefixeAuth);
    }
    sendHeader("location: ".$prefixeAuth);

  }

  public function logout(){
    global $prefixeAuth;

    $this->session->delete('auth');
    sendHeader("location: " .$prefixeAuth);
    return;
  }

}
