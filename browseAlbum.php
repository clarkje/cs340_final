<?php
ini_set('display_errors', 'On');

// Setup the MySQL connection
require('config/mysql.php');
$mysqli = new mysqli($db_host,$db_user,$db_pass,$db_schema);

if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

// Setup the template engine
require './lib/mustache/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$mustache = new Mustache_Engine(array(
    'template_class_prefix' => 'templates',
    'cache' => dirname(__FILE__).'tmp/cache',
    'cache_file_mode' => 0666, // Please, configure your umask instead of doing this :)
    'cache_lambda_templates' => true,
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views/partials'),
    'escape' => function($value) {
        return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
    },
    'logger' => new Mustache_Logger_StreamLogger('php://stderr'),
    'strict_callables' => true,
    'pragmas' => [Mustache_Engine::PRAGMA_FILTERS],
));
$tpl = $mustache->loadTemplate('browseAlbum');


function getAlbums($mysqli) {


  $query = "SELECT album_id, artist.name, genre.description, album.name, release_date, total_tracks
              FROM album
              INNER JOIN artist ON album.artist_id = artist.artist_id
              INNER JOIN genre ON album.genre_id = genre.genre_id
              ";

  if(!($stmt = $mysqli->prepare($query))){
    echo "Prepare failed: "  . $mysqli->errno . " " . $mysqli->error;
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

    $result[$i]["tracks"] = getTracks($mysqli, $album_id);
    $i++;
  }
  return $result;
}

function getTracks($mysqli, $album_id) {

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

  if(!($track_stmt = $mysqli->prepare($track_query))){
    echo "Prepare failed: "  . $mysqli->errno . " " . $mysqli->error;
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

$result = getAlbums($mysqli);

echo $tpl->render(array('albums' => $result));
?>
