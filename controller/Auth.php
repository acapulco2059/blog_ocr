  <?php

  require_once "model/User.php";

  class Auth{

      private $session;
      private $user;

      public function __construct($session){
          $this->session = $session;
          $this->user = new User();
      }


      public function getPage($url){
        $this->url = $url;
        $todo = $url[1];                                        //la fonction à appeler par défaut est le premier segment
        if ($todo == "") $todo = "home";                        //si il n'est pas défini on affiche la page d'accueil
        if ( !method_exists ( $this, $todo ) ) $todo = "home";  //si la fonction n'existe pas on affiche la page d'accueil
        return $this->$todo();
      }


      private function home(){
        $errorMess = $this->session->getFlashes();
        var_dump($errorMess);

        return [
          "{{ errorMess }}" => $errorMess,
          "{{ urlFront }}" => $GLOBALS["prefixeFront"],
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
              header('location: '.$GLOBALS["prefixeBack"]);
              exit();
          }else{
              $this->session->setFlash("danger", "Mot de passe ou identifiant incorrecte ");
              header('location: '.$GLOBALS["prefixeAuth"]);
          }

        }
      }

      public function restrict(){
          if(!$this->session->read('auth')){
              $this->session->setFlash('danger', "Vous n'avez pas le droit d'accéder à cette page");
              header('Location: login.php');
              exit();
          }
      }
  }
