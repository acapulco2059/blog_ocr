<?php

require_once "view/View.php";
require_once "model/Model.php";

class Comment
{
  public function showReportComment(){
    //affiche arcticle à la une
    $req = [
        "data"  => [
          'ID',
          'idPost',
          'author AS "{{ author }}" ',
          'comment AS "{{ comment }}" ',
          'DATE_FORMAT(date, \'%d/%m/%Y\') AS "{{ date }}" ',
          'report AS "{{ report }}" '
        ],
        "where" => [ "report >= 1" ],
        "from"  => "comments",
        "order" => "report DESC"
      ];
    $data = Model::select($req);
    $html = View::makeLoopHtml($data["data"], "reportComment");

    return $html;
  }

  public function allPostComments($postId){
    $req = [
      "data" => [
        'ID AS "{{ idComment }}"',
        'idPost AS "{{ idPost }}"',
        'author AS "{{ author }}"',
        'comment AS "{{ comment }}"',
        'DATE_FORMAT(date, \'%d/%m/%Y\') AS "{{ date }}"',
        'report AS "{{ report }}"'
      ],
      "where" => [ "idPost =" .$postId ],
      "from" => "comments"
    ];
    $data = Model::select($req);
    $html = View::makeLoopHtml($data["data"], "comment");

    return $html;
  }

  public function singleComment($commentId){
    $req = [
      "data" => [
        'ID',
        'idPost',
        'author AS "{{ author }}"',
        'comment AS "{{ comment }}"',
        'DATE_FORMAT(date, \'%d/%m/%Y\') AS "{{ date }}"',
        'report AS "{{ report }}"'
      ],
      "where" => [ "ID =" .$commentId ],
      "from" => "comments"
    ];
    $data = Model::select($req);
    $html = View::makeHtml($data["data"], "comment");

    return $html;
  }

  public function addComment($value){
    $req = [
      "into" => "comments",
      "data" => [
        'author',
        'comment',
        'date',
        'idPost'
      ],
      "value" => [
        ':author',
        ':comment',
        ':date = NOW()',
        ':idPost'
      ]
    ];
    $data = Model::create($req, $value);
  }

  public function updateComment($data){
    $req = [
      "from" => "comments",
      "data" => [
        'author = ' .$data["author"],
        'comment = ' .$data["comment"],
        'date = ' .$data["date"],
        'report = ' .$data["report"]
      ],
      "where" => ["ID = " .$data["id"]]
    ];
    $data = Model::update($req);
  }

  public function incrementReport($data){
    $req = [
      "from" => "comments",
      "data" => [
        "report = " .$data["report"]
      ],
      "where" => ["ID = ".$data["id"]]
    ];
    $data = Model::update($req);
  }

  public function deleteComment($commentId){
    $req = [
      "from" => "comments",
      "where" => ["ID =" .$commentId]
    ];
    $data = Model::delete($req);
  }
}
