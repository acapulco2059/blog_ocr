<?php

class postComment
{
  protected $author = filter_var($_POST["author"], FILTER_SANITIZE_STRING);
  protected $comment = filter_var($_POST["comment"], FILTER_SANITIZE_STRING);
  protected $idPost = filter_var($_POST["idPost"], FILTER_SANITIZE_NUMBER_INT);



  public function author(){
    return $this->author;
  }

  public function comment(){
    return $this->comment;
  }

  public function idPost(){
    return $this->idPost;
  }




}

?>
