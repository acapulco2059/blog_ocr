<?php

require_once "model/Model.php";
require_once "view/View.php";

class Post
{
  private $model;
  private $view;

  public function __construct()
  {
    $this->model = new Model();
    $this->view = new View();
  }

  public function listPost(){
    //affiche la liste des articles
    $req = [
        "data"  => [
          'ID AS "{{ id }}"',
          'title AS "{{ title }}"'
        ],
        "from"  => "posts"
    ];
    $data = $this->model->select($req);
    $html = $this->view->makeLoopHtml($data["data"], "titreArticle");

    return $html;
  }

  public function post($postId){
    //affiche un article
    $req = [
      "data" => [
        'ID AS "{{ id }}" ',
        'title AS "{{ title }}" ',
        'content AS "{{ content }}"" ',
        'DATE_FORMAT(published, \'%d/%m/%Y\') AS "{{ published }}"'
      ],
      "from" => "post",
      "where" => [ "ID=" .$postId ]
    ];
    $data = $this->model->request($req, $postId);
    $html = $this->view->makeHtml($data["data"], "article");
  }

  public function posts(){
    //affiche la liste des articles
    $req = [
        "data"  => [
          'ID AS "{{ id }}"',
          'title AS "{{ title }}"',
          'content AS "{{ content }}" ',
          'DATE_FORMAT(published, \'%d/%m/%Y\') AS "{{ published }}"'
        ],
        "from"  => "posts"
    ];
    $data = $this->model->select($req);
    $html = $this->view->makeLoopHtml($data["data"], "article");

    return $html;
}
}
