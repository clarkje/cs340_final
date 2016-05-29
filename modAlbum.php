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
$tpl = $mustache->loadTemplate('manageAlbum');
$context = array();

// Get the list of genres for the dropdown
require_once('GenreQuery.php');
$genre = new GenreQuery($mysqli);
$context['genres'] = $genre->getGenres();

require_once('AlbumStatusQuery.php');
$astatus = new AlbumStatusQuery($mysqli);
$context['astatus'] = $astatus->getStatus();

// We're modifying data, include the accessor class
if (isset($_REQUEST['action'])) {
	require_once('AlbumQuery.php');
	$albumQuery = new AlbumQuery($mysqli);

	// $_REQUEST is the method agnostic version of $_GET or $_POST
	switch($_REQUEST['action']) {

		// Process the form submission
	  case "addAlbum":

			$error = $albumQuery->insertAlbum(
									$_POST['artist_id'], $_POST['genre'],$_POST['name'],
									$_POST['release_date'], $_POST['total_tracks']);

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Album Added Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Album Add Failed</div>";
			}

	  break;
		case "updateAlbum":

			$error = $albumQuery->updateAlbum(
				$_POST['album_id'],$_POST['artist_id'],$_POST['genre'],
				$_POST['name'],$_POST['release_date'],$_POST['total_tracks']
			);

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Album Updated Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Album Update Failed</div>";
			}
		break;
		case "addTrack":

			require_once('TrackQuery.php');
			$trackQuery = new TrackQuery($mysqli);

			$error = $trackQuery->addTrack($_REQUEST['album_id'],
				$_REQUEST['track_genre_id'],$_REQUEST['track_name'],
				$_REQUEST['track_rel_year'],$_REQUEST['track_num']);

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Track Added Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Track Add Failed</div>";
			}
		break;

		case "updateTrack":

			require_once('TrackQuery.php');
			$trackQuery = new TrackQuery($mysqli);

			$error = $trackQuery->updateTrack($_REQUEST['track_id'],
				$_REQUEST['track_genre_id'], $_REQUEST['track_name'],
				$_REQUEST['track_rel_year'], $_REQUEST['track_num']);

			// The update includes a new artist ID
			if(isset($_REQUEST['track_artist_id'])) {
				$error = $trackQuery->addArtist($_REQUEST['track_id'],$_REQUEST['track_artist_id']);
			}

			// The update includes a new composer ID
			if(isset($_REQUEST['track_composer_id'])) {
				$error = $trackQuery->addComposer($_REQUEST['track_id'],$_REQUEST['track_composer_id']);
			}

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Track Updated Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Track Update Failed</div>";
			}
		break;

		case "addCopy":

			$error = $albumQuery->addCopy($_REQUEST['album_id'],$_REQUEST['ainstance_status'],
													 $_REQUEST['ainstance_location']);

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Copy Added Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Add Copy Failed</div>";
			}

		break;
		case "deleteTrack":

			require_once('TrackQuery.php');
			$trackQuery = new TrackQuery($mysqli);

			$error = $trackQuery->deleteTrack($_REQUEST['track_id']);

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Track Deleted Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Track Delete Failed</div>";
			}
	}
}

// Populate the form with existing data
$result = $albumQuery->getAlbum($_REQUEST['album_id']);
$copy_result = $albumQuery->getCopies($_REQUEST['album_id']);

$context['album_id'] = $result[0]['album_id'];
$context['album_name'] = $result[0]['album_name'];
$context['artist_id'] = $result[0]['artist_id'];
$context['artist_name'] = $result[0]['artist_name'];
$context['genre_id'] = $result[0]['genre_id'];
$context['release_date'] = $result[0]['release_date'];
$context['rel_year'] = $result[0]['rel_year'];
$context['total_tracks'] = $result[0]['total_tracks'];
$context['tracks'] = $result[0]['tracks'];
$context['copies'] = $result[0]['copies'];

// Populate the variables to pass to the template

if (isset($alert)) {
	$context['alert'] = $alert;
}

echo $tpl->render($context);
?>
