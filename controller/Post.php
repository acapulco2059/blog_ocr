<?php

namespace blog\controller;

use blog\view\View;

class Post
{

    protected $postManager;

    public function __construct()
    {
        $this->postManager = new PostManager();
    }

    public function selectAll()
    {
        $count = $this->postManager->countPost();
        $posts = $this->postManager->allPosts('postTitleTable');

        if ($count != 0) {
            return $posts;
        } else {
            $posts = "Il n'y a pas encore de chapitre sur le blog, veuillez nous en excuser";
            return $posts;
        }
    }

    public function insert()
    {

        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $data = [
            "title" => $title,
            "content" => $content,
            "published" => date("Y-m-d")
        ];

        if (!empty($title) && !empty($content)) {
            $this->postManager->addPost($data);
        } else {
            $this->session->setFlash("danger", "Tous les champs ne sont pas remplis !");
        }

    }

    public function update($url)
    {
        $id = $url;
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        $data = [
            "id" => $id,
            "title" => $title,
            "content" => $content,
            "modified" => date("Y-m-d")
        ];

        if (isset($id) && $id > 0) {
            if (!empty($title) && !empty($content)) {
                $this->postManager->updatePost($data);
            } else {
                $this->session->setFlash("danger", "Tous les champs ne sont pas remplis !");
            }
        } else {
            $this->session->setFlash("danger", "Aucun identifiant de billet envoyé");
        }

    }

    public function getChaptersListTable()
    {
        $data = [
            "{{ col1 }}" => "Publié le",
            "{{ col2 }}" => "Dernière modification",
            "{{ col3 }}" => "Titre",
            "{{ col4 }}" => "Modifier",
            "{{ col5 }}" => "Supprimer"
        ];

        $html = View::makehtml($data, "table");
        return $html;
    }

    public function addTinyMCE()
    {
        global $prefixeFront;
        global $prefixeBack;
        global $prefixeAuth;

        $title = "Ajout d'un nouveau Chapitre";
        $content = $this->tinyMCEinit();
        $data = [
            "{{ title }}" => $title,
            "{{ content }}" => $content,
            "{{ urlAdmin }}" => $prefixeBack,
            "{{ urlAuth }}" => $prefixeAuth,
            "{{ urlFront }}" => $prefixeFront
        ];
        return $data;
    }

    public function modifyTinyMCE($url)
    {
        global $prefixeFront;
        global $prefixeBack;
        global $prefixeAuth;

        if (!empty($url)) {
            $title = "Modification de chapitre";
            $table = $this->postManager->allPosts("postTitleTable");
            $content = $this->getChaptersListTable();
            $content .= $this->tinyMCEmodify($url);
        } else {
            $title = "Modification de chapitre";
            $table = $this->postManager->allPosts("postTitleTable");
            $content = $this->getChaptersListTable();
            $content .= $this->tinyMCEinit();
        }
        $data = [
            "{{ title }}" => $title,
            "{{ content }}" => $content,
            "{{ tableBody }}" => $table,
            "{{ urlAdmin }}" => $prefixeBack,
            "{{ urlAuth }}" => $prefixeAuth,
            "{{ urlFront }}" => $prefixeFront
        ];
        return $data;
    }


    public function tinyMCEinit()
    {
        global $prefixeBack;

        $data = [
            "{{ urlAdmin }}" => $prefixeBack,
            "{{ postFunc }}" => "addPo",
            "{{ postTitle }}" => "Titre du chapitre",
            "{{ postContent }}" => "Contenu du chapitre"
        ];
        $html = View::makehtml($data, "backTINYMCE");
        return $html;
    }

    public function tinyMCEmodify($url)
    {
        global $prefixeBack;

        $postTitle = $this->postManager->showSinglePost($url, "title");
        $postContent = $this->postManager->showSinglePost($url, "content");
        $postFunc = "updatePo/" . $url;

        $data = [
            "{{ urlAdmin }}" => $prefixeBack,
            "{{ postFunc }}" => $postFunc,
            "{{ postTitle }}" => $postTitle,
            "{{ postContent }}" => $postContent
        ];
        $html = View::makehtml($data, "backTINYMCE");
        return $html;
    }

}
