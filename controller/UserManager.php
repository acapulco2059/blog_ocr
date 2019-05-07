<?php

namespace blog\controller;

use blog\model\Model;

class UserManager
{

    public function verify($data)
    {
        $req = [
            "data" => [
                'ID',
                'username',
            ],
            "from" => "users",
            "where" => [
                "username = '" . $data['username'] . "'",
                "password = '" . $data['password'] . "'"
            ]
        ];

        $data = Model::select($req);
        return $data;
    }

}
