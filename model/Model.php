<?php

class Model {

  private static $db;

  static function init(){
    self::$db = new PDO('mysql:host='.$GLOBALS["db"]["host"].';dbname='.$GLOBALS["db"]["dataBase"].';charset=utf8', $GLOBALS["db"]["user"], $GLOBALS["db"]["password"]);
    if (!$GLOBALS["envProd"]) self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    unset($GLOBALS["db"]);
  }


  public static function select($args){              // build an sql request from args array

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
    return self::request($req);
  }

  public static function create($args, $value){

    $req  = 'INSERT INTO ' .$args["into"]."(";
    $req .= implode( " , ", $args["data"]).")";
    $req .= " VALUES (" .implode(" , ", $args["value"]).")";

    return self::request($req, $value);
  }

  public static function update($args){
    $req  = 'UPDATE ' .$args['from'];
    $req .= ' SET ' .implode( " , ", $args["data"]);
    $req .= ' WHERE ' .implode(" AND ", $args["where"]);
    return self::request($req);
  }

  public static function delete($args){

    $req  = 'DELETE FROM ' .$args["from"];
    $req .= ' WHERE ' .$args["where"];

    return self::request($req);
    }



  public static function request($sql, $data=NULL) {

    try {
      if ($data == NULL) {                     // query
        $resultat = self::$db->query($sql);
      }
      else {                                   // prepare and execute
        $resultat = self::$db->prepare($sql);
        $resultat->execute($data);
      }
      $data = $resultat->fetchAll();           //store result
      $resultat->closeCursor();                //close request
      if (!isset($data[1])) {
        if (isset($data[0])) $data=$data[0];   //if there is only one answer we keep it instead of an array
      }

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
