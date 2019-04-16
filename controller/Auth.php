<?php

require_once "model/UserManager.php";

class Auth {

  private $session;
  private $user;

  public function __construct($session = NULL){
    $this->session = $session;
    $this->user = new UserManager();
  }


  public function getPage($url){
    $this->url = $url;
    $todo = $url[1];                                        //la fonction à appeler par défaut est le premier segment
    if ($todo == "") $todo = "home";                        //si il n'est pas défini on affiche la page d'accueil
    if ( !method_exists ( $this, $todo ) ) $todo = "home";  //si la fonction n'existe pas on affiche la page d'accueil
    return $this->$todo();
  }


  private function home(){
    // $errorMess = $this->session->getFlashes();
    return [
      "{{ urlFront }}" => ShortURL::getPrefixeFront(),
      "{{ pageTitle }}" => 'Connexion'
    ];
  }


  public function connect($user){
    $this->session->write('auth', $user);
  }

  public function login(){
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $value = [
      "username" => $username
    ];

    if(!empty($username) && !empty($password)) {
      $user = $this->user->verify($value);
      if(password_verify($password, $user["data"]["password"])){
        $this->connect($user);        
        exit(header("Location: ".ShortURL::getPrefixeBack()));
      }
      $this->session->setFlash("danger", "Mot de passe ou identifiant incorrect");
      header("location: ".ShortURL::getPrefixeAuth());
    }
    header("location: ".ShortURL::getPrefixeAuth());

  }

  public function logout(){
    $this->session->delete('auth');
    header("location: " .ShortURL::getPrefixeAuth());
    return;
  }

}
