<?php

namespace blog\model;

class UserManager {

  public function verify($value){
    $req = [
      "data" => [
        'ID',
        'username',
        'password'
      ],
      "from"  => "users",
      "where" => ["username = '" .$value['username']."'"]
    ];

    $data = Model::select($req);
    return $data;
  }

}
