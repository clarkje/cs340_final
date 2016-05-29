<?php

class AlbumStatusQuery {

  private $mysqli = null;

  function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }

  // Returns an array of status codes and descriptions
  function getStatus() {

    $query = "SELECT astatus_id, description FROM astatus";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }
    $stmt->execute();
    $stmt->bind_result($astatus_id, $description);
    $stmt->store_result();

    $result = array();
    while($stmt->fetch()) {
      $result[] = array('astatus_id' => $astatus_id,
                        'description' => $description);
    }

    if($this->mysqli->error) {
      return null;
    } else {
      return $result;
    }
  }
}
?>
