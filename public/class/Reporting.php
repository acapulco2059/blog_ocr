<?php

namespace blog\apps;

class Reporting {

  private $commentManager;

  const TO_VALIDATE = 0;
  const VALIDATE = 1;
  const COMFIRMED = 2;

  public function __construct() {
    $this->commentManager = new \blog\model\CommentManager();
  }

  public function increment($url){
    $statut = $this->commentManager->commentStatut($url[2]);

    $data = [
      "report" => (int)$statut["data"]["report"] +1,
      "reportDate" => date('Y-m-d'),
      "id" => $url[2]
    ];

    $this->commentManager->incrementReport($data);
    global $prefixeFront;

    header("Location: ".$prefixeFront."chapitre/" .$url[1]);
  }

  public function toValidate($url){
    $data = [
      "reportStatut" => self::TO-VALIDATE,
      "id" => $url[2]
    ];

    $this->commentManager->reportStatut($data);
  }

  public function validate($url){
    $data = [
      "reportStatut" => self::VALIDATE,
      "id" => $url[2]
    ];

    $this->commentManager->reportStatut($data);
  }

  public function confirmed($url){
    $data = [
      "reportStatut" => self::COMFIRMED,
      "id" => $url[2]
    ];

    $this->commentManager->reportStatut($data);
  }

}

?>
