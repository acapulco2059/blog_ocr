<?php

require_once "model/PostManager.php";
require_once "model/CommentManager.php";

class Back {

  protected $postManager;
  protected $commentManager;
  protected $comment;
  protected $post;
  protected $session;
  protected $reporting;
  protected $url;


  public function __construct($session) {
    $this->commentManager = new CommentManager();
    $this->comment = new Comment();
    $this->postManager = new PostManager();
    $this->post= new Post();
    $this->reporting = new Reporting();
    $this->session = $session;

    if(!isset($_SESSION['auth'])) {
      $this->session->setFlash("danger", "Vous n'avez pas l'autorisation d'accèder à cette page, identifiez vous");
      header("location: ".$GLOBALS["prefixeAuth"]);
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
    $title = "Accueil";
    $content = "";
    $validate = "";
    $report = "";

    return [
      "{{ urlAdmin }}" => shortURL::getPrefixeBack(),
      "{{ urlAuth }}" => shortURL::getPrefixeAuth(),
      "{{ urlFront }}" => shortURL::getPrefixeFront(),
      "{{ title }}" => $title,
      "{{ content }}" => $content
    ];

  }

  public function commentValidate(){
    $title = "Commentaire(s) à valider";
    $content = $this->comment->getModerateCommentsTable();
    $table = $this->comment->getModerateComments("moderateCommentTable");

    return [
      "{{ urlAdmin }}" => shortURL::getPrefixeBack(),
      "{{ urlAuth }}" => shortURL::getPrefixeAuth(),
      "{{ urlFront }}" => shortURL::getPrefixeFront(),
      "{{ title }}" => $title,
      "{{ content }}" => $content,
      "{{ tableBody }}" => $table
    ];
  }

  public function commentReport(){
    $title = "Commentaire(s) signalé(s)";
    $content = $this->comment->getReportCommentsTable();
    $table = $this->comment->getReportComments("reportCommentTable");
    return [
      "{{ urlAdmin }}" => shortURL::getPrefixeBack(),
      "{{ urlAuth }}" => shortURL::getPrefixeAuth(),
      "{{ urlFront }}" => shortURL::getPrefixeFront(),
      "{{ title }}" => $title,
      "{{ content }}" => $content,
      "{{ tableBody }}" => $table
    ];
  }


  public function chapterAdd(){
    $title = "Ajout d'un nouveau Chapitre";
    $content = $this->post->tinyMCEinit();
    return [
      "{{ urlAdmin }}" => shortURL::getPrefixeBack(),
      "{{ urlAuth }}" => shortURL::getPrefixeAuth(),
      "{{ urlFront }}" => shortURL::getPrefixeFront(),
      "{{ title }}" => $title,
      "{{ content }}" => $content
    ];
  }

  public function chapterModify(){
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
      "{{ urlAdmin }}" => shortURL::getPrefixeBack(),
      "{{ urlAuth }}" => shortURL::getPrefixeAuth(),
      "{{ urlFront }}" => shortURL::getPrefixeFront(),
      "{{ title }}" => $title,
      "{{ content }}" => $content,
      "{{ tableBody }}" => $table
    ];
  }

  public function deleteCo(){
    $this->commentManager->deleteComment($this->url[2]);
    header("Location: ".shortURL::getPrefixeBack());
  }

  public function deletePo(){
    $this->postManager->deletePost($this->url[2]);
    $this->commentManager->deleteComments($this->url[2]);

    header("Location: ".shortURL::getPrefixeBack(). "chapterModify/");
  }

  public function addPo(){
    $this->post->insert();
    header("Location: ".shortURL::getPrefixeBack(). "chapterModify/");
  }

  public function updatePo() {
    $this->post->update($this->url[2]);
    header("Location: ".shortURL::getPrefixeBack(). "chapterModify/");
  }

  public function comValidate(){
    $this->reporting->validate($this->url);
    header("Location: ".shortURL::getPrefixeBack(). "commentValidate/");
  }

  public function comConfirmed(){
    $this->reporting->confirmed($this->url);
    header("Location: ".shortURL::getPrefixeBack(). "commentReport/");
  }

}
