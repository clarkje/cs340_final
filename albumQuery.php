<?php

class albumQuery {

  private $mysqli = null;

  function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }

  // Returns an array of albums and their tracks
  function getAlbum($album_id = -1) {

    $query = "SELECT album_id, artist.name, genre.description, album.name, release_date, total_tracks
                FROM album
                INNER JOIN artist ON album.artist_id = artist.artist_id
                INNER JOIN genre ON album.genre_id = genre.genre_id
                ";

  	if($album_id > -1) {
  		$query .= "WHERE album_id = ?";
  	}

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

  	if($album_id > -1) {
  		$stmt->bind_param("i", $album_id);
  	}

    $stmt->execute();
    $stmt->bind_result($album_id, $artist_name, $genre_desc, $album_name, $release_date, $total_tracks);
    $stmt->store_result();

    $i = 0;
    while($stmt->fetch()) {

      // Format the date so that just the year is displayed
      $dt = new DateTime($release_date);
      $rel_year = date_format($dt, "Y");

      $result[$i] = array(
          "album_id" => $album_id,
          "artist_name" => $artist_name,
          "genre_desc" => $genre_desc,
          "album_name" => $album_name,
          "release_date" => $release_date,
          "rel_year" => $rel_year,
          "total_tracks" => $total_tracks
      );

      $result[$i]["tracks"] = $this->getTracks($album_id);
      $i++;
    }
    return $result;
  }

  // Returns an array of tracks for an album_id
  function getTracks($album_id) {

    // Get the tracks for the album
    $track_query = "SELECT track.track_id, track.name, track.genre_id,
                           track.release_date, track.track_num,
                           artist.name, composer.first_name, composer.last_name
                    FROM track
                    INNER JOIN track_artist ON track_artist.track_id = track.track_id
                    INNER JOIN artist ON track_artist.artist_id = artist.artist_id
                    INNER JOIN track_composer ON track_composer.track_id = track.track_id
                    INNER JOIN composer ON track_composer.composer_id = composer.composer_id
                    WHERE album_id = ?";

    if(!($track_stmt = $this->mysqli->prepare($track_query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }
    $track_stmt->bind_param("i", $album_id);
    $track_stmt->execute();
    $track_stmt->bind_result($track_id, $track_name, $genre_id, $release_date,
    $track_num, $artist_name, $composer_first_name, $composer_last_name);
    $track_stmt->store_result();

    $tracks = array();
    while($track_stmt->fetch()) {
      $tracks[] = array(
          "track_id" => $track_id,
          "track_name" => $track_name,
          "genre_id" => $genre_id,
          "release_date" => $release_date,
          "track_num" => $track_num,
          "artist_name" => $artist_name,
          "composer_first_name" => $composer_first_name,
          "composer_last_name" => $composer_last_name
      );
    }
    return $tracks;
  }

}
?>
