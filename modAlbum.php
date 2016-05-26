<?php
ini_set('display_errors', 'On');

// Setup the MySQL Connection
require('config/mysql.php');
$mysqli = new mysqli($db_host,$db_user,$db_pass,$db_schema);

if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

// Setup the Template Engine
// Adapted from "Using all the options" Mustache Example at:
// https://github.com/bobthecow/mustache.php/wiki
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
$tpl = $mustache->loadTemplate('addAlbum');
$context = array();

// $_REQUEST is the method agnostic version of $_GET or $_POST
switch(isset($_REQUEST['action'])) {

	// Process the form submission
  case "addAlbum":
		$query = "INSERT INTO album
								(album_id, artist_id, genre_id, name, release_date, total_tracks)
								VALUES
								(?,?,?,?,?,?)";

		if(!($stmt = $mysqli->prepare($query))){
			echo "Prepare failed: "  . $mysqli->errno . " " . $mysqli->error;
		}

		$rdate = $_POST['release_date'].'-01-01';

		$stmt->bind_param("iiissi",$_POST['album_id'],$_POST['artist_id'],
							$_POST['genre'],$_POST['name'],$rdate,
							$_POST['total_tracks']);

		$stmt->execute();

		if(!$mysqli->error) {
			$alert = "<div class='alert alert-success' role='alert'>Album Added Successfully</div>";
		} else {
			$alert = "<div class='alert alert-danger' role='alert'>Album Add Failed</div>";
		}

		$stmt->close();

		$context['genres'] = $genres;

  break;
	case "editAlbum":
		// Populate the form with existing data

		$query = "SELECT album_id, artist_id, genre_id, name, release_date, total_tracks
							FROM album
							WHERE album_id = ?";

		if(!($stmt = $mysqli->prepare($query))){
			echo "Prepare failed: "  . $mysqli->errno . " " . $mysqli->error;
		}

		$stmt->bind_param("i",$_POST['album_id']);
		$stmt->execute();
		$stmt->bind_result($album_id, $artist_id, $genre_id, $release_date, $total_tracks);

		$context['genres'] = $genres;
		$context['album_id'] = $album_id;
		$context['artist_id'] = $artist_id;
		$context['genre_id'] = $genre_id;
		$context['release_date'] = $release_date;
		$context['total_tracks'] = $total_tracks;

	break;
}

// Get the list of genres for the dropdown

$query = "SELECT genre_id, description FROM genre";
if(!($stmt = $mysqli->prepare($query))){
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

// Populate the variables to pass to the template

if (isset($alert)) {
	$context['alert'] = $alert;
}



echo $tpl->render($context);
?>
