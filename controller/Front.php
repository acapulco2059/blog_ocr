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
    //affcihe une lise des articles
    $content = $this->post->allPosts();
    return [
      "{{ pageTitle }}"=> "Bienvenue",
      "{{ content }}"  => $content
    ];
  }

  private function chapitre(){                              // affiche la page d'un chapitre

    return [
      "{{ pageTitle }}" => $this->post->postTitle($this->url[1]),
      "{{ content }}"  => $this->post->showSinglePost($this->url[1]),
      "{{ comments }}" => $this->comment->allPostComments($this->url[1])
    ];

  }

  private function chapitres(){                             // affiche une page listant les chapitres

    $content = $this->post->allPosts();

    return [
      "{{ pageTitle }}"=> "Ensemble des chapitres",
      "{{ content }}"  => $content
    ];
  }
}
