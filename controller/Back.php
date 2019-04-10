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
    private $url;


    public function __construct($session) {
      $this->commentManager = new CommentManager();
      $this->comment = new Comment();
      $this->post= new Post();
      $this->postManager = new PostManager();
      $this->reporting = new Reporting();
      $this->session = $session;

      if(!isset($_SESSION['auth'])) {
        $this->session->setFlash("danger", "Vous n'avez pas l'autorisation d'accèder à cette page, identifiez vous");
        header("location: ".$GLOBALS["prefixeAuth"]);
        exit();
      }
    }


  public function getPage($url){
    $this->url = $url;
    $todo = $url[1];                                        //la fonction à appeler par défaut est le premier segment
    if ($todo == "") $todo = "home";                        //si il n'est pas défini on affiche la page d'accueil
    if ( !method_exists ( $this, $todo ) ) $todo = "home";  //si la fonction n'existe pas on affiche la page d'accueil
    return $this->$todo();
  }

  private function home(){

    $reports = $this->comment->getReportComments("reportCommentTable");
    $moderate = $this->comment->getModerateComments("moderateCommentTable");
    $articles = $this->post->selectAll();
    $articles = $this->postManager->allPosts("postTitleTable");

    return [
      "{{ urlAdmin }}" => $GLOBALS["prefixeBack"],
      "{{ urlAuth }}" => $GLOBALS['prefixeAuth'],
      "{{ pageTitle }}" => 'Admin',
      "{{ moderate }}" => $moderate,
      "{{ reports }}" => $reports,
      "{{ articles }}" => $articles,
      "{{ articleTitle }}" => "Titre de l'article",
      "{{ articleContent }}" => "Insérer ici votre contenu",
      "{{ postFunc }}" => "addPo"
    ];

  }

  private function postUpdate(){

    $reports = $this->comment->getReportComments("reportCommentTable");
    $moderate = $this->comment->getModerateComments("moderateCommentTable");
    $articles = $this->post->selectAll();
    $articleTitle = $this->postManager->showSinglePost($this->url[2], "title");
    $articleContent = $this->postManager->showSinglePost($this->url[2], "content");
    $postFunc = "updatePo/" .$this->url[2];

    return [
    "{{ urlAdmin }}" => $GLOBALS["prefixeBack"],
    "{{ urlAuth }}" => $GLOBALS['prefixeAuth'],
    "{{ pageTitle }}" => 'Modification de l\'article',
    "{{ moderate }}" => $reports,
    "{{ reports }}" => $reports,
    "{{ articles }}" => $articles,
    "{{ articleTitle }}" => $articleTitle,
    "{{ articleContent }}" => $articleContent,
    "{{ postFunc }}" => $postFunc
  ];
  }


  private function deleteCo(){
    $this->commentManager->deleteComment($this->url[2]);
    header("Location: ".$GLOBALS["prefixeBack"]);
  }

  private function deletePo(){
    $this->postManager->deletePost($this->url[2]);
    $this->commentManager->deleteComments($this->url[2]);

    header("Location: ".$GLOBALS["prefixeBack"]);
  }

  private function addPo(){
    $this->post->insert();
    header("Location: ".$GLOBALS["prefixeBack"]);
  }

  private function updatePo() {
    $this->post->update($this->url[2]);
    header("Location: ".$GLOBALS["prefixeBack"]);
  }

  private function comValidate(){
    $this->reporting->validate($this->url);
    header("Location: ".$GLOBALS["prefixeBack"]);
  }

  private function comConfirmed(){
    $this->reporting->confirmed($this->url);
    header("Location: ".$GLOBALS["prefixeBack"]);
  }

}
