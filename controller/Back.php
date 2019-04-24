<?php

namespace blog\controller;

class Back {

  protected $postManager;
  protected $commentManager;
  protected $comment;
  protected $post;
  protected $session;
  protected $reporting;
  protected $url;

  public function __construct($session) {
    global $prefixeAuth;

    $this->commentManager = new \blog\model\CommentManager();
    $this->comment = new \blog\apps\Comment();
    $this->postManager = new \blog\model\PostManager();
    $this->post= new \blog\apps\Post();
    $this->reporting = new \blog\apps\Reporting();
    $this->session = $session;

    if(!isset($_SESSION['auth'])) {
      $this->session->setFlash("danger", "Vous n'avez pas l'autorisation d'accèder à cette page, identifiez vous");
      sendHeader("location: ".$prefixeAuth);
    }
  }


  public function getPage($url){
    $this->url = $url;
    $todo = $url[1];                                        //la fonction à appeler par défaut est le premier segment
    if ($todo == "") $todo = "home";                        //si il n'est pas défini on affiche la page d'accueil
    if ( !method_exists ( $this, $todo ) ) $todo = "home";  //si la fonction n'existe pas on affiche la page d'accueil
    return $this->$todo();
  }

  public function home(){
    global $prefixeFront;
    global $prefixeBack;
    global $prefixeAuth;

    $title = "Accueil";
    $content = $this->comment->getBackHome();

    return [
      "{{ title }}" => $title,
      "{{ content }}" => $content,
      "{{ urlAdmin }}" => $prefixeBack,
      "{{ urlAuth }}" => $prefixeAuth,
      "{{ urlFront }}" => $prefixeFront,
    ];

  }

  public function commentValidate(){
    global $prefixeFront;
    global $prefixeBack;
    global $prefixeAuth;

    $title = "Commentaire(s) à valider";
    $content = $this->comment->getModerateCommentsTable();
    $table = $this->comment->getModerateComments("moderateCommentTable");

    return [
      "{{ title }}" => $title,
      "{{ content }}" => $content,
      "{{ tableBody }}" => $table,
      "{{ urlAdmin }}" => $prefixeBack,
      "{{ urlAuth }}" => $prefixeAuth,
      "{{ urlFront }}" => $prefixeFront
    ];
  }

  public function commentReport(){
    global $prefixeFront;
    global $prefixeBack;
    global $prefixeAuth;

    $title = "Commentaire(s) signalé(s)";
    $content = $this->comment->getReportCommentsTable();
    $table = $this->comment->getReportComments("reportCommentTable");
    return [
      "{{ title }}" => $title,
      "{{ content }}" => $content,
      "{{ tableBody }}" => $table,
      "{{ urlAdmin }}" => $prefixeBack,
      "{{ urlAuth }}" => $prefixeAuth,
      "{{ urlFront }}" => $prefixeFront
    ];
  }


  public function chapterAdd(){
    global $prefixeFront;
    global $prefixeBack;
    global $prefixeAuth;

    $title = "Ajout d'un nouveau Chapitre";
    $content = $this->post->tinyMCEinit();
    return [
      "{{ title }}" => $title,
      "{{ content }}" => $content,
      "{{ urlAdmin }}" => $prefixeBack,
      "{{ urlAuth }}" => $prefixeAuth,
      "{{ urlFront }}" => $prefixeFront
    ];
  }

  public function chapterModify(){
    global $prefixeFront;
    global $prefixeBack;
    global $prefixeAuth;

    if(!empty($this->url[2])){
      $title = "Modification de chapitre";
      $table = $this->postManager->allPosts("postTitleTable");
      $content = $this->post->getChaptersListTable();
      $content .= $this->post->tinyMCEmodify($this->url[2]);
    } else {
      $title = "Modification de chapitre";
      $table = $this->postManager->allPosts("postTitleTable");
      $content = $this->post->getChaptersListTable();
      $content .= $this->post->tinyMCEinit();
    }
    return [
      "{{ title }}" => $title,
      "{{ content }}" => $content,
      "{{ tableBody }}" => $table,
      "{{ urlAdmin }}" => $prefixeBack,
      "{{ urlAuth }}" => $prefixeAuth,
      "{{ urlFront }}" => $prefixeFront

    ];
  }

  public function deleteCo(){
    global $prefixeBack;

    $this->commentManager->deleteComment($this->url[2]);
    sendHeader("Location: ".$prefixeBack);
  }

  public function deletePo(){
    global $prefixeBack;

    $this->postManager->deletePost($this->url[2]);
    $this->commentManager->deleteComments($this->url[2]);

    sendHeader("Location: ".$prefixeBack. "chapterModify/");
  }

  public function addPo(){
    global $prefixeBack;

    $this->post->insert();
    sendHeader("Location: ".$prefixeBack. "chapterModify/");
  }

  public function updatePo() {
    global $prefixeBack;

    $this->post->update($this->url[2]);
    sendHeader("Location: ".$prefixeBack. "chapterModify/");
  }

  public function comValidate(){
    global $prefixeBack;

    $this->reporting->validate($this->url);
    sendHeader("Location: ".$prefixeBack. "commentValidate/");
  }

  public function comConfirmed(){
    global $prefixeBack;

    $this->reporting->confirmed($this->url);
    sendHeader("Location: ".$prefixeBack. "commentReport/");
  }

}
