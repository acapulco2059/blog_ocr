<?php

class Com {

  private $comment;

  public function __construct() {
    $this->comment = new Comment();
  }


  public function setComment($url) {
    $commentator = filter_input(INPUT_POST, 'commentator', FILTER_SANITIZE_STRING);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $date = date("Y-m-d");

    $data = [
      "commentator" => $commentator,
      "comment" => $comment,
      "idPost" => $url,
      "date" => $date,
      "reportStatut" => 0
    ];


    if (isset($url) && $url > 0)
    {
      if (!empty($commentator) && !empty($comment))
      {
        $this->comment->addComment($data);
      }
      else
      {
        $_SESSION["flash"]["Tous les champs ne sont pas remplis !"];
      }
    }
    else
    {
      $_SESSION["flash"]["Aucun identifiant de billet envoyé"];
    }

    header("Location: ".$GLOBALS["prefixeFront"]."chapitre/" .$url);

  }


  public function getComments($url){

    $countCom = $this->comment->countCommentPost($url);
    $count = $countCom["data"]["COUNT(*)"];

    if($count > 0) {
      $comment = $this->comment->allPostComments($url);
      return $comment;
    } else {
      $comment = "Pas de commentaires pour cet article";
      return $comment;
    }
  }

}
