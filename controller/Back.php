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

    $this->commentManager = new \blog\controller\CommentManager();
    $this->comment = new \blog\controller\Comment();
    $this->postManager = new \blog\controller\PostManager();
    $this->post= new \blog\controller\Post();
    $this->reporting = new \blog\controller\Reporting();

    if($session->get('auth') == null) {
      $session->setFlash("danger", "Vous n'avez pas l'autorisation d'accèder à cette page, identifiez vous");
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
    $html = $this->comment->validate();
    return $html;
  }

  public function commentReport(){
    $html = $this->comment->getReport();
    return $html;
  }


  public function chapterAdd(){
    $html = $this->post->addTinyMCE();
    return $html;
  }

  public function chapterModify(){
    $html = $this->post->modifyTinyMCE($this->url[2]);
    return $html;
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
