<?php

namespace blog\controller;

class Front
{

    protected $postManager;
    protected $commentManager;
    protected $comment;
    protected $reporting;
    protected $url;

    public function __construct()
    {
        $this->commentManager = new CommentManager();
        $this->postManager = new PostManager();
        $this->reporting = new Reporting();
        $this->comment = new Comment();
    }

    public function getPage($url)
    {
        $this->url = $url;
        $todo = $url[0];                                        //la fonction à appeler par défaut est le premier segment
        if ($todo == "") $todo = "home";                        //si il n'est pas défini on affiche la page d'accueil
        if (!method_exists($this, $todo)) $todo = "home";  //si la fonction n'existe pas on affiche la page d'accueil
        return $this->$todo();
    }


    public function home()
    {                                  // affiche la page d'accueil
        global $prefixeFront;
        //affcihe le dernier post (chapitre) publié
        $postId = "(SELECT MAX(id) FROM posts)";
        $content = $this->postManager->showSinglePost($postId, "post");

        return [
            "{{ pageTitle }}" => "Billet simple pour l'Alaska",
            "{{ content }}" => $content,
            "{{ comment }}" => "",
            "{{ prefixe }}" => $prefixeFront
        ];
    }

    public function chapitre()
    {
        global $prefixeFront;
        // affiche la page d'un chapitre
        $content = $this->postManager->showSinglePost($this->url[1], "postSingle");
        $comments = $this->comment->getComments($this->url[1]);
        $title = $this->postManager->showSinglePost($this->url[1], "title");


        return [
            "{{ pageTitle }}" => $title,
            "{{ content }}" => $content,
            "{{ comment }}" => $comments,
            "{{ prefixe }}" => $prefixeFront
        ];
    }

    public function chapitres()
    {                             // affiche une page listant les chapitres
        global $prefixeFront;

        $content = $this->postManager->allPosts("postShort");

        return [
            "{{ pageTitle }}" => "Ensemble des chapitres",
            "{{ content }}" => $content,
            "{{ comment }}" => "",
            "{{ prefixe }}" => $prefixeFront
        ];
    }

    public function addReport()
    {

        $this->reporting->increment($this->url);

    }

    public function postCo()
    {
        global $prefixeFront;


        $this->comment->setComment($this->url[1]);
        sendHeader("Location: " . $prefixeFront . "chapitre/" . $this->url[1]);

    }
}
