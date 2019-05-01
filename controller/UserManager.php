<?php

namespace blog\controller;

class UserManager {

  public function verify($data){
    $req = [
      "data" => [
        'ID',
        'username',
        'password'
      ],
      "from"  => "users",
      "where" => ["username = '" .$data['username']."'"]
    ];

    $data = \blog\model\Model::select($req);
    return $data;
  }

}
