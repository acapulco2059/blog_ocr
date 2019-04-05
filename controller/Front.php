<?php

require_once "model/Post.php";
require_once "model/Comment.php";

class Front {

  protected $post;
  protected $comment;
  protected $com;
  protected $reporting;
  protected $url;

  public function __construct()
  {
    $this->comment = new Comment();
    $this->post = new Post();
    $this->reporting = new Reporting();
    $this->com = new Com();
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
    $content = $this->post->showSinglePost($postId, "singleArticle");
    $comment = $this->com->getComments($postId);

    return [
      "{{ pageTitle }}"=> "Billet simple pour l'Alaska",
      "{{ content }}"  => $content,
      "{{ comment }}" => $comment,
      "{{ prefixe }}" =>$GLOBALS["prefixeFront"]
    ];
  }

  private function chapitre(){
    // affiche la page d'un chapitre
    $content = $this->post->showSinglePost($this->url[1], "singleArticle");
    $comment = $this->com->getComments($this->url[1]);
    $title = $this->post->showSinglePost($this->url[1], "title");


    return [
      "{{ pageTitle }}" => $title,
      "{{ content }}"  => $content,
      "{{ comment }}" => $comment,
      "{{ prefixe }}" =>$GLOBALS["prefixeFront"]
    ];
  }

  private function chapitres(){                             // affiche une page listant les chapitres

    $content = $this->post->allPosts("article");

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

    $this->com->setComment($this->url[1]);
    
  }
}
