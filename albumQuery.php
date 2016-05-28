<?php

class AlbumQuery {

  private $mysqli = null;

  function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }


  // Updates the album record identified by album_id
  function updateAlbum($album_id, $artist_id, $genre_id, $album_name, $release_year, $total_tracks) {

    $query = "UPDATE album SET
              artist_id = ?,
              genre_id = ?,
              album_name = ?,
              release_date = ?,
              total_tracks = ?,
              WHERE album_id = ?
    ";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $release_date = $release_year.'-01-01';

    $stmt->bind_param("iissii", $album_id, $artist_id, $genre_id, $album_name,
              $release_date, $total_tracks);

    if (isset($this->mysqli->error)) {
      return $this->mysqli->error;
    } else {
      return null;
    }

    $stmt->close();

  }

  // Inserts a new album record into the database
  function insertAlbum($artist_id, $genre_id, $album_name, $release_date, $total_tracks) {

      $query = "INSERT INTO album
                  (artist_id, genre_id, name, release_date, total_tracks)
                VALUES
                  (?,?,?,?,?)";

      if(!($stmt = $this->mysqli->prepare($query))){
        echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
      }

      $rdate = $_POST['release_date'].'-01-01';

      $stmt->bind_param("iissi",$artist_id,$genre_id,$album_name,$rdate,$total_tracks);

      $stmt->execute();

      if(isset($this->mysqli->error)) {
        return $this->mysqli->error;
      } else {
        return null;
      }

      $stmt->close();

  }

  // Returns an array of albums and their tracks
  function getAlbum($album_id = -1) {

    $query = "SELECT album_id, artist.name, album.artist_id, genre.description, album.genre_id, album.name, release_date, total_tracks
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
    $stmt->bind_result($album_id, $artist_name, $artist_id, $genre_desc, $genre_id, $album_name, $release_date, $total_tracks);
    $stmt->store_result();

    $i = 0;
    while($stmt->fetch()) {

      // Format the date so that just the year is displayed
      $dt = new DateTime($release_date);
      $rel_year = date_format($dt, "Y");

      $result[$i] = array(
          "album_id" => $album_id,
          "album_name" => $album_name,
          "artist_name" => $artist_name,
          "artist_id" => $artist_id,
          "genre_desc" => $genre_desc,
          "genre_id" => $genre_id,
          "album_name" => $album_name,
          "release_date" => $release_date,
          "rel_year" => $rel_year,
          "total_tracks" => $total_tracks
      );

      $result[$i]["tracks"] = $this->getTracks($album_id);
      $i++;
    }

    // HACK: If this were a real application, exceptions would be good here.
    // Since no data is being modified, I'm just going to punt and return null
    // if the query fails.
    if ($this->mysqli->error) {
      return null;
    }

    $stmt->close();
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
    $track_stmt->close();
    return $tracks;
  }

}
?>
