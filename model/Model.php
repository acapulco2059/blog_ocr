<?php

class Model {
  private $db;
  public function __construct() {
    $this->db = new PDO('mysql:host='.$GLOBALS["db"]["host"].';dbname='.$GLOBALS["db"]["dataBase"].';charset=utf8', $GLOBALS["db"]["user"], $GLOBALS["db"]["password"]);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (!$GLOBALS["envProd"]) $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    unset($GLOBALS["db"]);
  }

  public function select($args){              // build an sql request from args array

    // main things :
    $req  = 'SELECT '.implode(", ", $args["data"]);
    $req .= " FROM ".$args["from"];

    // optional things :
    // WHERE
    if (isset($args["where"])) $req .= ' WHERE '.implode(" AND ", $args["where"]);

    // ORDER BY
    if (isset($args["order"])) $req .= " ORDER BY ".$args["order"];

    // LIMIT
    if (isset($args["limit"])) $req .= " LIMIT ".$args["limit"];

    // launch request and return result
    return $this->request($req);
  }

  private function request($sql, $data=NULL) {
    try {
      if ($data == NULL) {                     // query
        $resultat = $this->db->query($sql);
      }
      else {                                   // prepare and execute
        $resultat = $this->db->prepare($sql);
        $resultat->execute($data);
      }
      $data = $resultat->fetchAll();           //store result
      $resultat->closeCursor();                //close request
      if (!isset($data[1])) $data=$data[0];    //if there is only one answer we keep it instead of an array
      return [
        "succeed" => TRUE,
        "data"    => $data
      ];
    }
    catch(Exception $e) {
      return [
        "succeed" => FALSE,
        "data"    => $e
      ];
    }
  }
}
