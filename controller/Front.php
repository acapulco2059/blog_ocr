<?php

require_once "model/PostManager.php";
require_once "model/CommentManager.php";

class Front {

  protected $postManager;
  protected $commentManager;
  protected $comment;
  protected $reporting;
  protected $url;

  public function __construct()
  {
    $this->commentManager = new CommentManager();
    $this->postManager = new PostManager();
    $this->reporting = new Reporting();
    $this->comment = new Comment();
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
    $content = $this->postManager->showSinglePost($postId, "singleArticle");
    $comments = $this->comment->getComments($postId);

    return [
      "{{ pageTitle }}"=> "Billet simple pour l'Alaska",
      "{{ content }}"  => $content,
      "{{ comment }}" => $comments,
      "{{ prefixe }}" =>$GLOBALS["prefixeFront"]
    ];
  }

  private function chapitre(){
    // affiche la page d'un chapitre
    $content = $this->postManager->showSinglePost($this->url[1], "singleArticle");
    $comments = $this->comment->getComments($this->url[1]);
    $title = $this->postManager->showSinglePost($this->url[1], "title");


    return [
      "{{ pageTitle }}" => $title,
      "{{ content }}"  => $content,
      "{{ comment }}" => $comments,
      "{{ prefixe }}" =>$GLOBALS["prefixeFront"]
    ];
  }

  private function chapitres(){                             // affiche une page listant les chapitres

    $content = $this->postManager->allPosts("article");

    return [
      "{{ pageTitle }}"=> "Ensemble des chapitres",
      "{{ content }}"  => $content,
      "{{ comment }}" => "",
      "{{ prefixe }}" =>$GLOBALS["prefixeFront"]
    ];
  }

  private function addReport(){

    $this->reporting->increment($this->url);

  }

  private function postCo(){

    $this->comment->setComment($this->url[1]);
    header("Location: ".$GLOBALS["prefixeFront"]."chapitre/" .$this->url[1]);

  }
}
