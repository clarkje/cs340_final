<?php

class ComposerQuery {

  private $mysqli;

  function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }

  function getComposerCount() {

    $query = "SELECT count(*) FROM composer";

    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->store_result();

    while($stmt->fetch()) {
      $total = $total;
    }

    $stmt->close();

    if($this->mysqli->error) {
      return null;
    } else {
      return $total;
    }
  }


  // Returns an array of artists, supports limits for pagination
  function getComposer($composer_id) {

    $query = "SELECT composer_id, first_name, last_name
              FROM composer
              WHERE composer_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i",$composer_id);

    $stmt->execute();
    $stmt->bind_result($composer_id, $first_name, $last_name);
    $stmt->store_result();

    while($stmt->fetch()) {
    	$result = array("composer_id" => $composer_id,
    										"composer_first_name" => $first_name,
                        "composer_last_name" => $last_name);
    }
    $stmt->close();

    if ($this->mysqli->error) {
      return null;
    } else {
      return $result;
    }
  }

  // Returns an array of artists, supports limits for pagination
  function getComposers($offset = 0, $limit = 0, $order_by = "last_name") {

    $query = "SELECT composer_id, first_name, last_name
              FROM composer
              ORDER BY " . $order_by . " ASC ";

    // Support windowing for pagination
    if($limit > 0 || $offset > 0) {
      $query .= " LIMIT ?,? ";
    }

    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    if($limit > 0 || $offset > 0) {
      $stmt->bind_param("ii",$offset,$limit);
    }
    $stmt->execute();
    $stmt->bind_result($composer_id, $first_name, $last_name);
    $stmt->store_result();

    $result = array();
    while($stmt->fetch()) {
    	$result[] = array("composer_id" => $composer_id,
    										"composer_first_name" => $first_name,
                        "composer_last_name" => $last_name);
    }
    $stmt->close();

    if ($this->mysqli->error) {
      return null;
    } else {
      return $result;
    }
  }

  function deleteComposer($composer_id) {

    $query = "DELETE FROM composer WHERE composer_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i",$composer_id);
    $stmt->execute();

    if ($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }

  function updateComposer($composer_id, $first_name, $last_name) {

    $query = "UPDATE composer SET
              first_name = ?,
              last_name = ?
              WHERE composer_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("ssi",$first_name,$last_name,$composer_id);
    $stmt->execute();

    if ($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }
}
?>
