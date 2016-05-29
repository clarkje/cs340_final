<?php

class AlbumInstanceQuery {

  private $mysqli;

  function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }

  function getAlbumInstance($ainstance_id) {

    $query = "SELECT ainstance.ainstance_id, ainstance.astatus_id,
                     astatus.description, ainstance.location
              FROM ainstance
              INNER JOIN astatus ON ainstance.astatus_id = astatus.astatus_id
              WHERE ainstance.ainstance_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i", $ainstance_id);
    $stmt->execute();
    $stmt->bind_result($ainstance_id, $astatus_id, $astatus_description,
                       $ainstance_location);

    while($stmt->fetch()) {
      $result = array(
        'ainstance_id' => $ainstance_id,
        'astatus_id' => $astatus_id,
        'astatus_description' => $astatus_description,
        'ainstance_location' => $ainstance_location
      );
    }

    if($this->mysqli->error) {
      return null;
    } else {
      return $result;
    }
  }

  function deleteAlbumInstance($ainstance_id) {

    $query = "DELETE FROM ainstance
              WHERE ainstance_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i", $ainstance_id);
    $stmt->execute();

    if ($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }
}
?>
