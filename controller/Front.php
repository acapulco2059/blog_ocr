<?php

class Front
{

  public function getPage($url){
    $todo = $url[0];                                        //la fonction à appeler par défaut est le premier segment
    if ($todo == "") $todo = "home";                        //si il n'est pas défini on affiche la page d'accueil
    if ( !method_exists ( $this, $todo ) ) $todo = "home";  //si la fonction n'existe pas on affiche la page d'accueil
    return $this->$todo();
  }

  private function home(){                                  // affiche la page d'accueil
    require_once "controller/Post.php";
    $post = new Post();

    //affiche arcticle à la une
    $content  = $post->showFeaturedPost();

    //affcihe une lise des articles
    $content .= $post->listPost();

    return [
      "{{ pageTitle }}"=>"bienvenue",
      "{{ content }}"  =>$content
    ];
  }

  private function chapitre(){                              // affiche la page d'un chapitre
    require_once "controller/Post.php";
    $post = new Post();

    //affiche l'article selectionner
    $content = $post->post();

    return [
      "{{ pageTitle }}"=> "{{ title }}",
      "{{ content }}"  =>$content
    ];
  }

  private function chapitres(){                             // affiche une page listant les chapitres
    require_once "controller/Post.php";
    $posts = new Post();

    $content = $posts->posts();

    return [
      "{{ pageTitle }}"=> "Ensemble des chapitres",
      "{{ content }}"  =>$content
    ]
  }
}
