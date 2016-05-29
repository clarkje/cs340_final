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

    $stmt->close();

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
        'total_tracks' => $total_tracks,
        'track_artists' => $this->getArtists($track_id),
        'composers' => $this->getComposers($track_id)
        );
    }

    $stmt->close();

    if ($this->mysqli->error) {
      return null;
    } else {
      return $result;
    }
  }

  // Returns an array of tracks associated with
  // the current album ID
  function getTrack($track_id) {

    $query = "SELECT track.track_id, track.genre_id, genre.description,
                    track.name, track.release_date, track.track_num,
                    album.total_tracks
              FROM track
              INNER JOIN genre ON track.genre_id = genre.genre_id
              INNER JOIN album ON track.album_id = album.album_id
              WHERE track.track_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i", $track_id);

    $stmt->execute();
    $stmt->bind_result($track_id, $genre_id, $genre_description, $name, $release_date, $track_num, $total_tracks);
    $stmt->store_result();

    while($stmt->fetch()) {

      // Format the date so that just the year is displayed
      $dt = new DateTime($release_date);
      $rel_year = date_format($dt, "Y");

      $result = array(
        'track_id' => $track_id,
        'track_genre_id' => $genre_id,
        'track_genre_description' => $genre_description,
        'track_name' => $name,
        'track_release_date' => $release_date,
        'track_rel_year' => $rel_year,
        'track_num' => $track_num,
        'total_tracks' => $total_tracks,
        'track_artists' => $this->getArtists($track_id),
        'composers' => $this->getComposers($track_id)
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

  // Returns an array of artist IDs and names associated with a track
  function getArtists($track_id) {

    $query = "SELECT track_artist.artist_id, artist.name
              FROM track_artist
              INNER JOIN artist ON artist.artist_id = track_artist.artist_id
              WHERE track_artist.track_id = ?";

              if(!($stmt = $this->mysqli->prepare($query))){
                echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
              }

    $stmt->bind_param("i",$track_id);
    $stmt->execute();
    $stmt->bind_result($artist_id, $artist_name);
    $stmt->store_result();


    $result = array();
    while($stmt->fetch()) {
      $result[] = array('artist_id' => $artist_id, 'artist_name' => $artist_name);
    }

    $stmt->close();

    if ($this->mysqli->error) {
        return null;
    } else {
        return $result;
    }
  }

  // Returns an array of the composer IDs and names associated with a track
  function getComposers($track_id) {

    $query = "SELECT track_composer.composer_id, composer.first_name, composer.last_name
              FROM track_composer
              INNER JOIN composer ON track_composer.composer_id = composer.composer_id
              WHERE track_composer.track_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i",$track_id);
    $stmt->execute();
    $stmt->bind_result($composer_id, $first_name, $last_name);
    $stmt->store_result();

    $result = array();
    while($stmt->fetch()) {
      $result[] = array('composer_id' => $composer_id,
                    'composer_first_name' => $first_name,
                    'composer_last_name' => $last_name);
    }

    $stmt->close();

    if ($this->mysqli->error) {
        return null;
    } else {
        return $result;
    }
  }

  // Associates an artist with a track in a 1:n relationship
  function addArtist($track_id, $artist_id) {

      $query = "INSERT INTO track_artist (track_id, artist_id) VALUES (?,?)";

      if(!($stmt = $this->mysqli->prepare($query))){
        echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
      }

      $stmt->bind_param("ii",$track_id, $artist_id);
      $stmt->execute();

      $stmt->close();

      if ($this->mysqli->error) {
        return $this->mysqli->error;
      } else {
        return null;
      }
  }

  function addComposer($track_id, $composer_id) {

    $query = "INSERT INTO track_composer (track_id, composer_id) VALUES (?,?)";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("ii",$track_id, $composer_id);
    $stmt->execute();

    $stmt->close();

    if ($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }

  function deleteArtist($track_id, $artist_id) {

    // track_id and artist_id must be valid indices
    if($track_id <= 0 || $artist_id <=0) {
      return "Error: track_id and artist_id must be valid values";
    }

    $query = "DELETE FROM track_artist
              WHERE track_id = ? AND artist_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("ii",$track_id, $artist_id);
    $stmt->execute();

    $stmt->close();

    if ($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }

  function deleteComposer($track_id, $composer_id) {

    $query = "DELETE FROM track_composer
              WHERE track_id = ? AND composer_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("ii",$track_id, $composer_id);
    $stmt->execute();

    $stmt->close();

    if ($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }

  function deleteTrack($track_id) {

    $query = "DELETE FROM track
              WHERE track_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i",$track_id);
    $stmt->execute();

    $stmt->close();

    if ($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }
}
