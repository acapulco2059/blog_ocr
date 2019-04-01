<?

require_once "model/Post.php";
require_once "model/Comment.php";

class Back
{
    protected $post;
    protected $comment;
    private $url;

    public function __construct($user = NULL)
    {
      // if(isset($user)){
        $this->comment = new Comment();
        $this->post = new Post();
      // } else {
      //   // $_SESSION["flash"]["danger"][""];
      //
      //   header("location: ".$GLOBALS["prefixeFront"]);
      // }

    }


  public function getPage($url){
    $this->url = $url;
    $todo = $url[1];                                        //la fonction à appeler par défaut est le premier segment
    if ($todo == "") $todo = "home";                        //si il n'est pas défini on affiche la page d'accueil
    if ( !method_exists ( $this, $todo ) ) $todo = "home";  //si la fonction n'existe pas on affiche la page d'accueil
    return $this->$todo();
  }

  private function home(){

    $reports = $this->comment->showReportComment();
    $articles = $this->post->allPosts("posttitleTable");

    return [
      "{{ urlAdmin }}" => $GLOBALS["prefixeBack"],
      "{{ pageTitle }}" => 'Admin',
      "{{ reports }}" => $reports,
      "{{ articles }}" => $articles,
      "{{ articleTitle }}" => "Titre de l'article",
      "{{ articleContent }}" => "Insérer ici votre contenu",
      "{{ postFunc }}" => "addPo"
    ];

  }

  private function postUpdate(){

    $reports = $this->comment->showReportComment();
    $articles = $this->post->allPosts("postTitleTable");
    $articleTitle = $this->post->showSinglePost($this->url[2], "title");
    $articleContent = $this->post->showSinglePost($this->url[2], "content");
    $postFunc = "updatePo/" .$this->url[2];

    return [
    "{{ urlAdmin }}" => $GLOBALS["prefixeBack"],
    "{{ pageTitle }}" => 'Modification de l\'article',
    "{{ reports }}" => $reports,
    "{{ articles }}" => $articles,
    "{{ articleTitle }}" => $articleTitle,
    "{{ articleContent }}" => $articleContent,
    "{{ postFunc }}" => $postFunc
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
      "title" => $title,
      "content" =>$content,
      "published" =>date("Y-m-d")
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

  private function updatePo(){

    $id = $this->url[2];
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);


    $data = [
      "id" => $id,
      "title" => $title,
      "content" => $content,
      "modified" => date("Y-m-d")
    ];

    if (isset($id) && $id > 0)
    {
      if (!empty($title) && !empty($content))
      {
        $this->post->updatePost($data);
      }
      else
      {
        throw new Exception('Tous les champs ne sont pas remplis !');
        }
    }
    else
    {
      throw new Exception('Aucun identifiant de billet envoyé');
    }

    header("Location: ".$GLOBALS["prefixeBack"]);

  }

}
