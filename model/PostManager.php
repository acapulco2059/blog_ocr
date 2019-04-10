<?php

require_once "model/Model.php";
require_once "view/View.php";

class PostManager {

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
    $html = View::makeLoopHtml($data["data"], "articleTitle");

    return $html;
  }

  public function showSinglePost($postId, $template){
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
    $html = View::makeHtml($data["data"], $template);

    return $html;
  }

  public function allPosts($template){
    //affiche la liste des articles
    $req = [
        "data"  => [
          'ID AS "{{ id }}"',
          'title AS "{{ title }}"',
          'content AS "{{ content }}"',
          'SUBSTR(content,1 , 1000) AS "{{ shortContent }}"',
          'DATE_FORMAT(published, \'%d/%m/%Y\') AS "{{ published }}"',
          'DATE_FORMAT(modified, \'%d/%m/%Y\') AS "{{ modified }}"'
        ],
        "from"  => "posts"
    ];


    $data = Model::select($req);
    $count = count($data["data"]);
    for($i=0; $i < $count; $i++) {
      $data["data"][$i]["{{ url }}"] = $GLOBALS["prefixeFront"]."chapitre/".$data["data"][$i]["{{ id }}"];
    }
    $html = View::makeLoopHtml($data["data"], $template);

    return $html;
  }

  public function countPost() {
    $req = [
      "data" => [
        "count" => "(*)"
      ],
      "from" => "posts",
    ];

    $data = Model::selectCount($req);
    return $data;
  }


  public function addPost($value){
    $req = [
      "into" => "posts",
      "data" => [
        'title' => $value["title"],
        'content' => $value["content"],
        'published' => $value["published"]
      ],
    ];
    $data = Model::insert($req, $value);
  }

  public function updatePost($data){
    $req = [
      "from" => "posts",
      "data" => [
        'title' => $data["title"],
        'content' => $data["content"],
        'modified' => $data["modified"]
      ],
      "where" => "ID = " .$data["id"]
    ];
    $data = Model::update($req);
  }

  public function deletePost($postId){
    $req = [
      "from" => "posts",
      "where" => "ID =" .$postId
    ];
    $data = Model::delete($req);
  }
}
