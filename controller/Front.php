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
      "{{ comment }}" => ""
    ];
  }

  private function chapitre(){
    // affiche la page d'un chapitre
    $content = $this->post->showSinglePost($this->url[1]);
    $comment = $this->comment->allPostComments($this->url[1]);

    return [
      "{{ pageTitle }}" => $this->post->postTitle($this->url[1]),
      "{{ content }}"  => $content,
      "{{ comment }}" => $comment
    ];
  }

  private function chapitres(){                             // affiche une page listant les chapitres

    $content = $this->post->allPosts();

    return [
      "{{ pageTitle }}"=> "Ensemble des chapitres",
      "{{ content }}"  => $content,
      "{{ comment }}" => ""
    ];
  }

  private function addReport(){

    $data = [
      "report" => "report + 1",
      "id" => $this->url[2]
    ];

    $this->comment->incrementReport($data);

    header("Location: http://localhost:8888/Projet/blog_poo/chapitre/" .$this->url[1]);
  }

  private function post(){

    $data = [
      
    ];

  }
}
