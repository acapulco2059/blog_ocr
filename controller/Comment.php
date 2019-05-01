<?php

namespace blog\controller;

class Comment {

  protected $commentManager;

  public function __construct() {
    $this->commentManager = new \blog\controller\CommentManager();
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

  public function validate() {
    global $prefixeFront;
    global $prefixeBack;
    global $prefixeAuth;

    $title = "Commentaire(s) à valider";
    $content = $this->getModerateCommentsTable();
    $table = $this->getModerateComments("commentModerateTable");

    $data = [
      "{{ title }}" => $title,
      "{{ content }}" => $content,
      "{{ tableBody }}" => $table,
      "{{ urlAdmin }}" => $prefixeBack,
      "{{ urlAuth }}" => $prefixeAuth,
      "{{ urlFront }}" => $prefixeFront
    ];
    return $data;
  }


  public function getModerateCommentsTable(){
    $data = [
      "{{ col1 }}" => "Date",
      "{{ col2 }}" => "Autheur",
      "{{ col3 }}" => "Commentaire",
      "{{ col4 }}" => "Supprimer",
      "{{ col5 }}" => "Valider"
    ];

    $html = \blog\view\View::makehtml($data, "table");
    return $html;
  }

  public function getReport() {
    global $prefixeFront;
    global $prefixeBack;
    global $prefixeAuth;

    $title = "Commentaire(s) signalé(s)";
    $content = $this->getReportCommentsTable();
    $table = $this->getReportComments("commentReportTable");
    $data = [
      "{{ title }}" => $title,
      "{{ content }}" => $content,
      "{{ tableBody }}" => $table,
      "{{ urlAdmin }}" => $prefixeBack,
      "{{ urlAuth }}" => $prefixeAuth,
      "{{ urlFront }}" => $prefixeFront
    ];
    return $data;
  }

  public function getReportCommentsTable(){
    $data = [
      "{{ col1 }}" => "Date",
      "{{ col2 }}" => "Nombre de signalement",
      "{{ col3 }}" => "Commentaire",
      "{{ col4 }}" => "Supprimer",
      "{{ col5 }}" => "Valider"
    ];

    $html = \blog\view\View::makehtml($data, "table");
    return $html;
  }

  public function getBackHome(){
    $countReport = $this->commentManager->countReportComment();
    $countModerate = $this->commentManager->countModerateComment();

    $data = [
      "{{ validate }}" => $countModerate["data"]["COUNT(*)"],
      "{{ report }}" => $countReport["data"]["COUNT(*)"]
    ];

    $html = \blog\view\View::makeHtml($data, "backHome");
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
