<?php

class Reporting {

    private $comment;

    const TO_VALIDATE = 0;
    const VALIDATE = 1;
    const COMFIRMED = 2;

    public function __construct() {
      $this->comment = new Comment();
    }

    public function increment($url){
      $statut = $this->comment->commentStatut($url[2]);
      $data = [
        "report" => (int)$statut["data"]["report"] +1,
        "reportDate" => date('Y-m-d'),
        "id" => $url[2]
      ];


      $this->comment->incrementReport($data);

      header("Location: ".$GLOBALS["prefixeFront"]."chapitre/" .$url[1]);
    }

    public function toValidate($url){
      $data = [
        "reportStatut" => self::TO-VALIDATE,
        "id" => $url[2]
      ];

      $this->comment->reportStatut($data);
    }

    public function validate($url){
      $data = [
        "reportStatut" => self::VALIDATE,
        "id" => $url[2]
      ];

      $this->comment->reportStatut($data);
    }

    public function confirmed($url){
      $data = [
        "reportStatut" => self::COMFIRMED,
        "id" => $url[2]
      ];

      $this->comment->reportStatut($data);
    }

}

?>
