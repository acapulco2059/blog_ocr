<?php

class Post {

  protected $postManager;

  public function __construct() {
    $this->postManager = new PostManager();
  }

  public function selectAll() {
    $count = $this->postManager->countPost();
    $posts = $this->postManager->allPosts('postTitleTable');

    if($count != 0) {
      return $posts;
    } else {
      $posts = "Il n'y a pas encore d'article sur le blog, veuillez nous en excuser";
      return $posts;
    }
  }

  public function insert() {

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS	);

    $data = [
      "title" => $title,
      "content" =>$content,
      "published" =>date("Y-m-d")
    ];

    if (!empty($title) && !empty($content))
    {
      $this->postManager->addPost($data);
    }
    else
    {
      $this->session->setFlash("danger", "Tous les champs ne sont pas remplis !");
    }

  }

  public function update($url) {
    $id = $url;
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS	);


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
        $this->postManager->updatePost($data);
      }
      else {
        $this->session->setFlash("danger", "Tous les champs ne sont pas remplis !");
      }
    } else {
        $this->session->setFlash("danger", "Aucun identifiant de billet envoyé");
    }

  }

}
