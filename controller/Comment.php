<?php

require_once "model/Model.php";
require_once "view/View.php";

class Comment
{
  private $model;
  private $view;

  public function __construct()
  {
    $this->model = new Model();
    $this->view = new View();
  }

  public function showReportComment(){
    //affiche arcticle à la une
    $req = [
        "data"  => [
          'ID',
          'idPost',
          'author AS "{{ author }}"',
          'comment AS "{{ comment }}"',
          'DATE_FORMAT(date, \'%d/%m/%Y\') AS "{{ date }}"',
          'report AS "{{ report }}"'
        ],
        "where" => [ "report >= 1" ],
        "from"  => "comments"
      ];
    $data = $this->model->select($req);
    $html = $this->view->makeLoopHtml($data["data"], "");

    return $html;
  }
}
