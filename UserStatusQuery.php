<?php

class UserStatusQuery {

  private $mysqli;

  function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }

  function getStatusList() {

    $query = "SELECT ustatus_id, description FROM ustatus";
    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
    }

    $stmt->execute();
    $stmt->bind_result($ustatus_id, $description);

    $i = 0;
    $genres = array();
    while($stmt->fetch()) {
    	$genres[$i] = array("ustatus_id" => $ustatus_id,
    											"ustatus_description" => $description);
    	$i++;
    }

    $stmt->close();
    return $genres;
  }

  // Returns a string with the status description fieldd
  function getStatusName($ustatus_id) {
    $query = "SELECT ustatus_id, description
              FROM ustatus
              WHERE ustatus_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
    }
    $stmt->bind_param("i",$ustatus_id);
    $stmt->execute();
    $stmt->bind_result($description);

    $stmt->close();
    return $description;
  }
}

?>
