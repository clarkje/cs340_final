<?php
require_once('TrackQuery.php');

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
              name = ?,
              release_date = ?,
              total_tracks = ?
              WHERE album_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $release_date = $release_year.'-01-01';

    $stmt->bind_param("iissii", $artist_id, $genre_id, $album_name,
              $release_date, $total_tracks, $album_id);

    $stmt->execute();

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

      $trackQuery = new TrackQuery($this->mysqli);
      $result[$i]["tracks"] = $trackQuery->getTracks($album_id);
      $result[$i]['copies'] = $this->getCopies($album_id);
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

  // Creates a new ainstance record for a supplied album ID
  function addCopy($album_id, $astatus_id, $location) {

    $query = "INSERT INTO ainstance
              (album_id, astatus_id, location)
              VALUES
              (?,?,?)";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("iis", $album_id, $astatus_id, $location);
    $stmt->execute();

    if($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }

  // Updates the supplied ainstance record
  function updateCopy($ainstance_id, $astatus_id, $location) {
    $query = "UPDATE ainstance SET
                astatus_id = ?,
                location = ?
              WHERE ainstance_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("isi", $astatus_id, $location, $ainstance_id);
    $stmt->execute();

    var_dump($this->mysqli->error);

    if($this->mysqli->error) {
      return $this->mysqli->error;
    } else {
      return null;
    }
  }

  // Returns a list of all album instances for a given album ID
  function getCopies($album_id) {

    $query = "SELECT ainstance.ainstance_id, ainstance.astatus_id,
                     astatus.description, ainstance.location
              FROM ainstance
              INNER JOIN astatus ON ainstance.astatus_id = astatus.astatus_id
              WHERE ainstance.album_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i", $album_id);
    $stmt->execute();
    $stmt->bind_result($ainstance_id, $astatus_id, $astatus_description,
                       $ainstance_location);

    $result = array();
    while($stmt->fetch()) {
      $result[] = array(
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

  function checkOut($ainstance_id, $user_id, $lendingPeriod) {

    $checkOut =  date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
    $dueBy = date("Y-m-d", mktime(0,0,0, date("m") ,date("d")+$lendingPeriod, date("Y")));

    // Check the album instance out
    $query = "INSERT INTO
              ainstance_user
              (ainstance_id, user_id, checked_out, due_by)
              VALUES
              (?,?,?,?)";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("iiss", $album_id, $user_id, $checkOut, $dueBy);
    $stmt->execute();
    $stmt->close();

    // Set the instance status to checked out
    $query = "UPDATE ainstance SET
              ainstance.astatus_id = (SELECT astatus_id FROM astatus
                                      WHERE description = 'Checked Out')
              WHERE ainstance_id = ?";

    if(!($stmt = $this->mysqli->prepare($query))){
      echo "Prepare failed: "  . $this->mysqli->errno . " " . $this->mysqli->error;
    }

    $stmt->bind_param("i", $ainstance_id);
    $stmt->execute();
    $stmt->close();

    if($this->mysqli->error) {
      return null;
    } else {
      return $result;
    }
  }
}
?>
