<?php

require_once "model/Model.php";
require_once "view/View.php";

class Post
{
  public function postTitle($idPost){
    $req = [
        "data"  => [
          'title AS "{{ title }}"'
        ],
        "from"  => "posts",
        "where" => [ "ID= ".$idPost ]
    ];
    $data = Model::select($req);
    $html = View::makeHtml($data["data"], "pageTitle");

    return $html;
  }

  public function listPosts(){
    //affiche la liste des articles
    $req = [
        "data"  => [
          'ID AS "{{ id }}"',
          'title AS "{{ title }}"'
        ],
        "from"  => "posts"
    ];
    $data = Model::select($req);
    $html = View::makeLoopHtml($data["data"], "titreArticle");

    return $html;
  }

  public function showSinglePost($postId){
    //affiche un article
    $req = [
      "data" => [
        'ID AS "{{ id }}" ',
        'title AS "{{ title }}" ',
        'content AS "{{ content }}" ',
        'DATE_FORMAT(published, \'%d/%m/%Y\') AS "{{ published }}" '
      ],
      "from" => "posts",
      "where" => ["ID= " .$postId]
    ];
    $data = Model::select($req);
    $html = View::makeHtml($data["data"], "singleArticle");

    return $html;
  }

  public function allPosts(){
    //affiche la liste des articles
    $req = [
        "data"  => [
          'ID AS "{{ id }}"',
          'title AS "{{ title }}"',
          'content AS "{{ content }}"',
          'SUBSTR(content,1 , 1000) AS "{{ shortContent }}"',
          'DATE_FORMAT(published, \'%d/%m/%Y\') AS "{{ published }}"'
        ],
        "from"  => "posts"
    ];


    $data = Model::select($req);
    $html = View::makeLoopHtml($data["data"], "article");

    return $html;
  }

  public function addPost(){
    $req = [
      "into" => "posts",
      "data" => [
        'published',
        'title',
        'content',
      ],
      "value" => [
        ':published = NOW()',
        ':title',
        ':content'
      ]
    ];
    $data = Model::create($req);
  }

  public function updatePost($postId){
    $req = [
      "from" => "posts",
      "data" => [
        'modified',
        'title',
        'content',
      ],
      "value" => [
        'NOW()',

      ],
      "where" => ["ID = " .$postId]
    ];
    $data = Model::update($req);
  }

  public function deletePost($postId){
    $req = [
      "from" => "posts",
      "where" => ["ID =" .$postId]
    ];
    $data = Model::delete($req);
  }
}
