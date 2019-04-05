<?php

require_once "view/View.php";
require_once "model/Model.php";

class CommentManager {
  public function showModerateComment($template){
    //affiche arcticle à la une
    $req = [
        "data"  => [
          'ID AS "{{ id }}"',
          'idPost',
          'author AS "{{ author }}" ',
          'comment AS "{{ comment }}" ',
          'DATE_FORMAT(date, \'%d/%m/%Y\') AS "{{ date }}" ',
          'DATE_FORMAT(reportDate, \'%d/%m/%Y\') AS "{{ reportDate }}" ',
          'report AS "{{ report }}" '
        ],
        "where" => [ "reportStatut = 0" ],
        "from"  => "comments",
        "order" => "Date DESC"
      ];
    $data = Model::select($req);

    if (!isset($data['data'][0])) {
      $tmp = $data["data"];
      $data["data"] = [];
      array_push($data["data"], $tmp);
    }

    $html = View::makeLoopHtml($data["data"], $template);
    return $html;
  }

  public function showReportComment($template){
    //affiche arcticle à la une
    $req = [
        "data"  => [
          'ID AS "{{ id }}"',
          'idPost',
          'author AS "{{ author }}"',
          'comment AS "{{ comment }}"',
          'DATE_FORMAT(date, \'%d/%m/%Y\') AS "{{ date }}" ',
          'DATE_FORMAT(reportDate, \'%d/%m/%Y\') AS "{{ reportDate }}" ',
          'report AS "{{ report }}" '
        ],
        "where" => [
          "report >= 1",
          "reportStatut != 2"
        ],
        "from"  => "comments",
        "order" => "reportDate DESC"
      ];
    $data = Model::select($req);

    if (!isset($data['data'][0])) {
      $tmp = $data["data"];
      $data["data"] = [];
      array_push($data["data"], $tmp);
    }

    $html = View::makeLoopHtml($data["data"], $template);
    return $html;
  }

  public function countCommentPost($postId) {
    $req = [
      "data" => [
        "count" => "(*)"
      ],
      "from" => "comments",
      "where" => [
        "reportStatut > 0",
        "idPost =" .$postId
        ]
    ];

    $data = Model::selectCount($req);
    return $data;
  }

  public function countModerateComment() {
    $req = [
      "data" => [
        "count" => "(*)"
      ],
      "from" => "comments",
      "where" => [
        "reportStatut = 0",
      ]
    ];

    $data = Model::selectCount($req);
    return $data;
  }

  public function countReportComment() {
    $req = [
      "data" => [
        "count" => "(*)"
      ],
      "from" => "comments",
      "where" => [
        "report >= 1",
        "reportStatut != 2"
      ]
    ];

    $data = Model::selectCount($req);
    return $data;
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
      "where" => [
        "idPost =" .$postId,
        "reportStatut >= 1"
     ],
      "from" => "comments",
      "order" => "report ASC"
    ];
    $data = Model::select($req);

    if (!isset($data['data'][0])) {
      $tmp = $data["data"];
      $data["data"] = [];
      array_push($data["data"], $tmp);
    }

    $count = count($data["data"]);
    for($i=0; $i < $count; $i++) {
      $data["data"][$i]["{{ prefixe }}"] = $GLOBALS["prefixeFront"];

    }
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

  public function commentStatut($commentId){
    $req = [
      "data" => [
        'report'
      ],
      "where" => [ "ID =" .$commentId ],
      "from" => "comments"
    ];
    $data = Model::select($req);
    return $data;
  }

  public function addComment($value){
    $req = [
      "into" => "comments",
      "data" => [
        "author" => $value["commentator"],
        'comment'=> $value["comment"],
        'idPost'=> $value["idPost"],
        'date' => $value["date"],
        'reportStatut' => $value["reportStatut"]
      ],
    ];
    $data = Model::insert($req);
  }

  public function updateComment($data){
    $req = [
      "from" => "comments",
      "data" => [
        'author' => $data["author"],
        'comment' =>$data["comment"],
        'date' => $data["date"],
        'report' => $data["report"]
      ],
      "where" => ["ID = " .$data["id"]]
    ];
    $data = Model::update($req);
  }

  public function incrementReport($data){
    $req = [
      "from" => "comments",
      "data" => [
        'report' => $data["report"],
        'reportDate' => $data["reportDate"]
      ],
      "where" => "ID = ".$data["id"]
    ];
    $data = Model::update($req);
  }

  public function reportStatut($data){
    $req = [
      "from" => "comments",
      "data" => [
        'reportStatut' => $data['reportStatut']
      ],
      'where' => "ID = ".$data["id"]
    ];
    $data = Model::update($req);
  }

  public function deleteComment($commentId){
    $req = [
      "from" => "comments",
      "where" => "ID =" .$commentId
    ];
    $data = Model::delete($req);
  }

  public function deleteComments($postId){
    $req = [
      "from" => "comments",
      "where" => "idPost =" .$postId
    ];
    $data = Model::delete($req);
  }

}
