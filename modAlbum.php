<?php
ini_set('display_errors', 'On');

// Setup the MySQL Connection
require_once('config/mysql.php');
$mysqli = new mysqli($db_host,$db_user,$db_pass,$db_schema);

if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

// Setup the Template Engine
// Adapted from "Using all the options" Mustache Example at:
// https://github.com/bobthecow/mustache.php/wiki
require_once('./lib/mustache/src/Mustache/Autoloader.php');
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

// Get the list of genres for the dropdown
require_once('genreQuery.php');
$genre = new genreQuery($mysqli);
$context['genres'] = $genre->getGenres();

// We're modifying data, include the accessor class
if (isset($_REQUEST['action'])) {
	require_once('albumQuery.php');
	$album = new albumQuery($mysqli);

	// $_REQUEST is the method agnostic version of $_GET or $_POST
	switch($_REQUEST['action']) {

		// Process the form submission
	  case "addAlbum":

			$error = $album->insertAlbum(
									$_POST['artist_id'], $_POST['genre'],$_POST['name'],
									$_POST['release_date'], $_POST['total_tracks']);

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Album Added Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Album Add Failed</div>";
			}

	  break;

		case "editAlbum":
			// Populate the form with existing data

			$result = $album->getAlbum($_REQUEST['album_id']);

			$context['album_id'] = $result[0]['album_id'];
			$context['album_name'] = $result[0]['album_name'];
			$context['artist_id'] = $result[0]['artist_id'];
			$context['artist_name'] = $result[0]['artist_name'];
			$context['genre_id'] = $result[0]['genre_id'];
			$context['release_date'] = $result[0]['release_date'];
			$context['rel_year'] = $result[0]['rel_year'];
			$context['total_tracks'] = $result[0]['total_tracks'];

		break;
	}
}

// Populate the variables to pass to the template

if (isset($alert)) {
	$context['alert'] = $alert;
}

echo $tpl->render($context);
?>
