<?php

class TrackQuery {

  private $mysqli = null;

  function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }

  function addTrack($album_id, $genre_id, $name, $rel_year, $track_num) {

    $release_date = $rel_year . '-01-01';

    $query = "INSERT INTO track
              (album_id, genre_id, name, release_date, track_num)
              VALUES (?,?,?,?,?)";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    var_dump($release_date);
    $stmt->bind_param("iissi", $album_id, $genre_id, $name, $release_date, $track_num);

    $stmt->execute();

    if($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }

  }

  // Returns an array of tracks associated with
  // the current album ID
  function getTracks($album_id) {

    $query = "SELECT track.track_id, track.genre_id, genre.description,
                    track.name, track.release_date, track.track_num,
                    album.total_tracks
              FROM track
              INNER JOIN genre ON track.genre_id = genre.genre_id
              INNER JOIN album ON track.album_id = album.album_id
              WHERE track.album_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i", $album_id);

    $stmt->execute();
    $stmt->bind_result($track_id, $genre_id, $genre_description, $name, $release_date, $track_num, $total_tracks);
    $stmt->store_result();

    $result = array();
    $i = 0;
    while($stmt->fetch()) {

      // Format the date so that just the year is displayed
      $dt = new DateTime($release_date);
      $rel_year = date_format($dt, "Y");

      $result[] = array(
        'track_id' => $track_id,
        'track_genre_id' => $genre_id,
        'track_genre_description' => $genre_description,
        'track_name' => $name,
        'track_release_date' => $release_date,
        'track_rel_year' => $rel_year,
        'track_num' => $track_num,
        'total_tracks' => $total_tracks
        );

    }

    $stmt->close();

    if ($this->mysqli->error) {
      return null;
    } else {
      return $result;
    }
  }

  function updateTrack($track_id, $genre_id, $track_name, $rel_year, $track_num) {

    $release_date = $rel_year."-01-01";

    $query = "UPDATE track SET
              genre_id = ?,
              name = ?,
              release_date = ?,
              track_num = ?
              WHERE track_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("issii",$genre_id, $track_name, $release_date, $track_num, $track_id);
    $stmt->execute();
    $stmt->close();

    if ($this->mysqli->error) {
        return $this->mysqli->error;
    } else {
        return null;
    }
  }

}
