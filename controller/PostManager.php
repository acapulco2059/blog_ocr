<?php

namespace blog\controller;

use blog\model\Model;
use blog\view\View;

class PostManager
{

    public function listPosts()
    {
        //affiche la liste des post (chapitres)
        $req = [
            "data" => [
                'ID AS "{{ id }}"',
                'title AS "{{ title }}"'
            ],
            "from" => "posts"
        ];
        $data = Model::select($req);
        $html = View::makeLoopHtml($data["data"], "postTitle");

        return $html;
    }

    public function lastPost($template)
    {
        $req = [
            "data" => [
                'MAX(ID) AS {{ id }}',
                'title AS "{{ title }}"',
                'content AS "{{ content }}" ',
                'DATE_FORMAT(published, \'%d/%m/%Y\') AS "{{ published }}" '
            ],
            "from" => "posts"
        ];
        $data = Model::select($req);
        $html = View::makeHtml($data["data"], $template);

        return $html;
    }

    public function showSinglePost($postId, $template)
    {
        //affiche un post (chapitre)
        $req = [
            "data" => [
                'ID AS "{{ id }}" ',
                'title AS "{{ title }}" ',
                'content AS "{{ content }}" ',
                'DATE_FORMAT(published, \'%d/%m/%Y\') AS "{{ published }}" '
            ],
            "from" => "posts",
            "where" => ["ID= " . $postId]
        ];
        $data = Model::select($req);
        $html = View::makeHtml($data["data"], $template);

        return $html;
    }

    public function allPosts($template)
    {
        //affiche la liste des post (chapitres)
        $req = [
            "data" => [
                'ID AS "{{ id }}"',
                'title AS "{{ title }}"',
                'content AS "{{ content }}"',
                'SUBSTR(content,1 , 1000) AS "{{ content }}"',
                'DATE_FORMAT(published, \'%d/%m/%Y\') AS "{{ published }}"',
                'DATE_FORMAT(modified, \'%d/%m/%Y\') AS "{{ modified }}"'
            ],
            "from" => "posts"
        ];


        $data = Model::select($req);
        $count = count($data["data"]);
        for ($i = 0; $i < $count; $i++) {
            global $prefixeFront;
            $data["data"][$i]["{{ url }}"] = $prefixeFront . "chapitre/" . $data["data"][$i]["{{ id }}"];
        }
        $html = View::makeLoopHtml($data["data"], $template);

        return $html;
    }

    public function countPost()
    {
        $req = [
            "data" => [
                "count" => "(*)"
            ],
            "from" => "posts",
        ];

        $data = Model::selectCount($req);
        return $data;
    }


    public function addPost($data)
    {
        $req = [
            "into" => "posts",
            "data" => [
                'title' => $data["title"],
                'content' => $data["content"],
                'published' => $data["published"]
            ],
        ];
        Model::insert($req, $data);
    }

    public function updatePost($data)
    {
        $req = [
            "from" => "posts",
            "data" => [
                'title' => $data["title"],
                'content' => $data["content"],
                'modified' => $data["modified"]
            ],
            "where" => "ID = " . $data["id"]
        ];
        Model::update($req);
    }

    public function deletePost($postId)
    {
        $req = [
            "from" => "posts",
            "where" => "ID =" . $postId
        ];
        Model::delete($req);
    }
}
