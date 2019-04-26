<?php

namespace blog\model;

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

    $data = Model::select($req);
    return $data;
  }

}
