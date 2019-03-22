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
    $todo = $url[1];                                        //la fonction à appeler par défaut est le premier segment
    if ($todo == "") $todo = "home";                        //si il n'est pas défini on affiche la page d'accueil
    if ( !method_exists ( $this, $todo ) ) $todo = "home";  //si la fonction n'existe pas on affiche la page d'accueil
    return $this->$todo();
  }

  private function home(){

    $template = "posttitleTable";

    $report = $this->comment->showReportComment();
    $articles = $this->post->allPosts($template);

    return [
      "{{ reports }}" => $report,
      "{{ articles }}" => $articles
    ];

  }

  private function deleteCo(){

    $this->comment->deleteComment($this->url[2]);

    header("Location: ".$GLOBALS["prefixeBack"]);
  }

  private function deletePo(){

    $this->post->deletePost($this->url[2]);
    $this->comment->deleteComments($this->url[2]);

    header("Location: ".$GLOBALS["prefixeBack"]);
  }

  private function addPo(){
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
  $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

  $data = [
    $title,
    $content,
  ];

  if (!empty($title) && !empty($content))
  {
    $this->post->addPost($data);
  }
  else
  {
    throw new Exception('Tous les champs ne sont pas remplis !');
  }


  header("Location: ".$GLOBALS["prefixeBack"]);
}

}
