<?

require_once "controller/Users.php";

class Login
{
    protected $user;

    public function __construct() {

    }

  public function getPage($url){
    $this->url = $url;
    $todo = $url[1];                                        //la fonction à appeler par défaut est le premier segment
    if ($todo == "") $todo = "home";                        //si il n'est pas défini on affiche la page d'accueil
    if ( !method_exists ( $this, $todo ) ) $todo = "home";  //si la fonction n'existe pas on affiche la page d'accueil
    return $this->$todo();
  }

  private function home(){

    return [
      "{{ prefixe }}" => $GLOBALS["prefixeFront"],
      "{{ pageTitle }}" => 'Connexion',
    ];
  }

  private function connect(){
    
  }

}
