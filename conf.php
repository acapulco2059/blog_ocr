<?php

//environnement production
$GLOBALS["envProd"] = false;

//database connexion
$GLOBALS["db"] = [
  "host"     => 'localhost',
  "user"     => 'root',
  "password" => 'root',
  "dataBase" => 'blog_1'
];

$GLOBALS["prefixeFront"] = "/Projet/blog_poo/";
$GLOBALS["prefixeBack"] = "/Projet/blog_poo/admin/";
$GLOBALS['prefixeAuth'] = "/Projet/blog_poo/auth/";
