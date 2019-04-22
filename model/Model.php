<?php

namespace blog\model;

class Model {

  private static $db;

  static function init(){
    global $dbLog;
    global $envProd;

    self::$db = new \PDO('mysql:host='.$dbLog["host"].';dbname='.$dbLog["dataBase"].';charset=utf8', $dbLog["user"], $dbLog["password"]);
    if (!$envProd) self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    unset($db);
  }


  public static function select($args){
    // Requête SQL principal "SELECT"
    $req  = 'SELECT '.implode(", ", $args["data"]);
    $req .= " FROM ".$args["from"];

    // Options :
    // "WHERE"
    if (isset($args["where"])) $req .= ' WHERE '.implode(" AND ", $args["where"]);

    // ORDER BY
    if (isset($args["order"])) $req .= " ORDER BY ".$args["order"];

    // LIMIT
    if (isset($args["limit"])) $req .= " LIMIT ".$args["limit"];

    // Lance la requête et retour le résultat.
    return self::selectRequest($req);
  }

  public static function selectCount($args){
    // Requête SQL principal "SELECT COUNT"
    $req  = 'SELECT COUNT'.implode(", ", $args["data"]);
    $req .= " FROM ".$args["from"];

    // Options :
    // WHERE
    if (isset($args["where"])) $req .= ' WHERE '.implode(" AND ", $args["where"]);

    return self::selectRequest($req);
  }

  public static function insert($args){
    // Requête SQL pour un "INSERT"
    // Récuparation de la valeur de l'array
    $value_columns    = array_keys($args["data"]);
    // Ajout de ":" devant la valeur de l'array
    $value_parameters = array_map(function($col) {return (':' . $col);}, $value_columns);

    $value_columns    = implode(', ', $value_columns);
    $value_parameters = implode(', ', $value_parameters);
    return self::request("INSERT INTO ".$args['into']." ($value_columns) VALUES ($value_parameters)", $args["data"]);
  }

  public static function update($args){
    // Requête SQL pour un "UPDATE"
    // Récuparation de la valeur de l'array
    $value_columns    = array_keys($args["data"]);
    // Ajout de ":" devant la valeur de l'array
    $value_parameters = array_map(function($col) {return (':' . $col);}, $value_columns);
    // Fusion des deux array
    $values           = array_combine($value_columns, $value_parameters);

    $valueSet = array();
    foreach($values as $key => $value) {
      $valueSet[] = $key . " = " . $value;
    }

    $valueSet = implode(' , ', $valueSet);

    return self::request("UPDATE ".$args['from']." SET $valueSet WHERE ".$args["where"], $args["data"]);

  }

  public static function delete($args){
    // Requête SQL pour un "DELETE"
    $req  = 'DELETE FROM ' .$args["from"];
    $req .= ' WHERE ' .$args["where"];
    return self::request($req);
  }



  public static function selectRequest($sql, $data=NULL) {

    try {
      if ($data == NULL) {                     // query
        $resultat = self::$db->query($sql);
      }
      else {                                   // prepare and execute
        $resultat = self::$db->prepare($sql);
        $resultat->execute($data);
      }
      $data = $resultat->fetchAll();
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

  public static function request($sql, $data=NULL) {

    try {
      if ($data == NULL) {                     // query
        $resultat = self::$db->query($sql);
        $resultat->closeCursor();                //close request
      }
      else {                                   // prepare and execute
        $resultat = self::$db->prepare($sql);
        $resultat->execute($data);
        $resultat->closeCursor();                //close request
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
