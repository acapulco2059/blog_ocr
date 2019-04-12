<?php

require_once "view/View.php";

class Comment {

  protected $commentManager;
  protected $view;

  public function __construct() {
    $this->commentManager = new CommentManager();
    $this->view = new View();
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
        $this->commentManager->addComment($data);
      }
      else
      {
        $this->session->setFlash("danger", "Tous les champs ne sont pas remplis !");
      }
    }
    else
    {
      $this->session->setFlash("danger", "Aucun identifiant de billet envoyé");
    }
  }


  public function getComments($url){
    $countCom = $this->commentManager->countCommentPost($url);
    $count = $countCom["data"]["COUNT(*)"];

    if($count > 0) {
      $comment = $this->commentManager->allPostComments($url);
      return $comment;
    } else {
      $comment = "Pas de commentaire pour cet article";
      return $comment;
    }
  }


  public function getModerateCommentsTable(){
    $data = [
      "{{ col1 }}" => "Date",
      "{{ col2 }}" => "Autheur",
      "{{ col3 }}" => "Commentaire",
      "{{ col4 }}" => "Supprimer",
      "{{ col5 }}" => "Valider"
    ];

    $html = $this->view->makehtml($data, "table");
    return $html;
  }

  public function getReportCommentsTable(){
    $data = [
      "{{ col1 }}" => "Date",
      "{{ col2 }}" => "Nombre de signalement",
      "{{ col3 }}" => "Commentaire",
      "{{ col4 }}" => "Supprimer",
      "{{ col5 }}" => "Valider"
    ];

    $html = $this->view->makehtml($data, "table");
    return $html;
  }


  public function getModerateComments($template){
    $countCom = $this->commentManager->countModerateComment();
    $count = $countCom["data"]["COUNT(*)"];

    if($count > 0) {
      $comment = $this->commentManager->showModerateComment($template);
      return $comment;
    } else {
      $comment = "Aucun commentaire à valider";
      return $comment;
    }

  }

  public function getReportComments($template){
    $countCom = $this->commentManager->countReportComment();
    $count = $countCom["data"]["COUNT(*)"];

    if($count > 0) {
      $comment = $this->commentManager->showReportComment($template);
      return $comment;
    } else {
      $comment = "Aucun commentaire signalé ";
      return $comment;
    }

  }


}
