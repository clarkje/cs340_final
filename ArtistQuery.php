<?php

class ArtistQuery {

  private $mysqli;

  function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }

  function getArtistCount() {

    $query = "SELECT count(*) FROM artist";

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
  function getArtist($artist_id) {

    $query = "SELECT artist_id, name
              FROM artist
              WHERE artist_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i",$artist_id);

    $stmt->execute();
    $stmt->bind_result($artist_id, $name);
    $stmt->store_result();

    while($stmt->fetch()) {
    	$result = array("artist_id" => $artist_id,
    										"artist_name" => $name);
    }
    $stmt->close();

    if ($this->mysqli->error) {
      return null;
    } else {
      return $result;
    }
  }

  // Returns an array of artists, supports limits for pagination
  function getArtists($offset = 0, $limit = 0) {

    $query = "SELECT artist_id, name
              FROM artist ";

    // Support windowing for pagination
    if($limit > 0 || $offset > 0) {
      $query .= "LIMIT ?,?";
    }

    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    if($limit > 0 || $offset > 0) {
      $stmt->bind_param("ii",$offset,$limit);
    }

    $stmt->execute();
    $stmt->bind_result($artist_id, $name);
    $stmt->store_result();

    $result = array();
    while($stmt->fetch()) {
    	$result[] = array("artist_id" => $artist_id,
    										"artist_name" => $name);
    }
    $stmt->close();

    if ($this->mysqli->error) {
      return null;
    } else {
      return $result;
    }
  }

  function deleteArtist($artist_id) {

    $query = "DELETE FROM artist WHERE artist_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
    }

    $stmt->bind_param("i",$artist_id);
    $stmt->execute();

    if ($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }


  function updateArtist($artist_id, $name) {

    $query = "UPDATE artist SET
              name = ?
              WHERE artist_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
    }

    $stmt->bind_param("si",$name,$artist_id);
    $stmt->execute();

    if ($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }

}
?>
