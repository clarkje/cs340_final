<?php

class UserTypeQuery {

  private $mysqli;

  function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }

  function getTypeList() {

    $query = "SELECT utype_id, description FROM utype";
    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
    }

    $stmt->execute();
    $stmt->bind_result($utype_id, $description);

    $i = 0;
    $genres = array();
    while($stmt->fetch()) {
    	$genres[$i] = array("utype_id" => $utype_id,
    											"utype_description" => $description);
    	$i++;
    }

    $stmt->close();
    return $genres;
  }

  // Returns a string with the status description fieldd
  function getTypeName($utype_id) {
    $query = "SELECT description
              FROM ustatus
              WHERE ustatus_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
    }
    $stmt->bind_param("i",$utype_id);
    $stmt->execute();
    $stmt->bind_result($description);

    $stmt->close();
    return $description;
  }
}

?>
