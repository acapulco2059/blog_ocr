<?

require_once "controller/Post.php";
require_once "controller/Comment.php";

class back
{
    protected $post;
    protected $comment;
    private $url;

    public function __construct()
    {
      $this->comment = new Comment();
      $this->post = new Post();
    }


  public function getPage($url){
    $this->url = $url;
    $todo = $url[0];                                        //la fonction à appeler par défaut est le premier segment
    if ($todo == "") $todo = "home";                        //si il n'est pas défini on affiche la page d'accueil
    if ( !method_exists ( $this, $todo ) ) $todo = "home";  //si la fonction n'existe pas on affiche la page d'accueil
    return $this->$todo();
  }

  private function home(){
     echo "Vous êtes sur la page admin";



  }
}
