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
$tpl = $mustache->loadTemplate('manageTrack');
$context = array();

// Get the list of genres for the dropdown
require_once('GenreQuery.php');
$genre = new GenreQuery($mysqli);
$context['genres'] = $genre->getGenres();

require_once('AlbumQuery.php');
$albumQuery = new AlbumQuery($mysqli);

require_once('AlbumStatusQuery.php');
$astatus = new AlbumStatusQuery($mysqli);
$context['astatus'] = $astatus->getStatus();

require_once('TrackQuery.php');
$trackQuery = new TrackQuery($mysqli);

if (isset($_REQUEST['action'])) {

	switch($_REQUEST['action']) {

		// Process the form submission
		case "updateTrack":

			$error = $trackQuery->updateTrack($_REQUEST['track_id'],
				$_REQUEST['genre'], $_REQUEST['track_name'],
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
		case "deleteArtist":

			$error = $trackQuery->deleteArtist($_REQUEST['track_id'],$_REQUEST['artist_id']);

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Artist Deleted Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Artist Delete Failed</div>";
			}
		break;
		case "deleteComposer":

			$error = $trackQuery->deleteComposer($_REQUEST['track_id'],$_REQUEST['composer_id']);

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Composer Deleted Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Composer Delete Failed</div>";
			}
		break;

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
$context['track'] = $trackQuery->getTrack($_REQUEST['track_id']);

// Since the template engine is brain-dead, we need to tell it which element to
// mark as selected in advance.
if(isset($context['track']) && isset($context['track']['track_genre_id'])) {
	for ($i = 0; $i < count($context['genres']); $i++) {
		if($context['genres'][$i]['genre_id'] == $context['track']['track_genre_id']) {
				$context['genres'][$i]['selected'] = true;
		}
	}
}

// Populate the variables to pass to the template

if (isset($alert)) {
	$context['alert'] = $alert;
}

echo $tpl->render($context);
?>
