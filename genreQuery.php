<?php

class genreQuery {

  private $mysqli;

  function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }

  function getGenres() {

    $query = "SELECT genre_id, description FROM genre";
    if(!($stmt = $this->mysqli->prepare($query))){
    	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
    }

    $stmt->execute();
    $stmt->bind_result($genre_id, $description);

    $i = 0;
    $genres = array();
    while($stmt->fetch()) {
    	$genres[$i] = array("genre_id" => $genre_id,
    											"description" => $description);
    	$i++;
    }
    return $genres;
  }
}

?>
