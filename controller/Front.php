<?php

require_once "controller/Post.php";
require_once "controller/Comment.php";

class Front
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



  private function home(){                                  // affiche la page d'accueil
    //affcihe le dernier article publié
    $postId = "(SELECT MAX(id) FROM posts)";
    $content = $this->post->showSinglePost($postId);
    return [
      "{{ pageTitle }}"=> "Billet simple pour l'Alaska",
      "{{ content }}"  => $content,
      "{{ comment }}" => "",
      "{{ prefixe }}" =>$GLOBALS["prefixeFront"]
    ];
  }

  private function chapitre(){
    // affiche la page d'un chapitre
    $content = $this->post->showSinglePost($this->url[1]);
    $comment = $this->comment->allPostComments($this->url[1]);

    return [
      "{{ pageTitle }}" => $this->post->postTitle($this->url[1]),
      "{{ content }}"  => $content,
      "{{ comment }}" => $comment,
      "{{ prefixe }}" =>$GLOBALS["prefixeFront"]
    ];
  }

  private function chapitres(){                             // affiche une page listant les chapitres

    $template = "article";

    $content = $this->post->allPosts($template);

    return [
      "{{ pageTitle }}"=> "Ensemble des chapitres",
      "{{ content }}"  => $content,
      "{{ comment }}" => "",
      "{{ prefixe }}" =>$GLOBALS["prefixeFront"]
    ];
  }

  private function addReport(){

    $data = [
      "report" => "report + 1",
      "id" => $this->url[2]
    ];

    $this->comment->incrementReport($data);

    header("Location: ".$GLOBALS["prefixeFront"]."chapitre/" .$this->url[1]);

  }

  private function postCo(){

    $commentator = filter_input(INPUT_POST, 'commentator', FILTER_SANITIZE_STRING);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $url = $this->url[1];

    $data = [
      $commentator,
      $comment,
      $url
    ];

    if (isset($this->url[1]) && $this->url[1] > 0)
    {
      if (!empty($commentator) && !empty($comment))
      {
        $this->comment->addComment($data);
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

    header("Location: ".$GLOBALS["prefixeFront"]."chapitre/" .$this->url[1]);
  }
}
