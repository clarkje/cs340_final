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
$tpl = $mustache->loadTemplate('checkOut');
$context = array();

// Get the list of genres for the dropdown
require_once('GenreQuery.php');
$genre = new GenreQuery($mysqli);
$context['genres'] = $genre->getGenres();

require_once('AlbumQuery.php');
$albumQuery = new AlbumQuery($mysqli);

// Populate the status dropdown
require_once('AlbumStatusQuery.php');
$astatus = new AlbumStatusQuery($mysqli);
$context['astatus'] = $astatus->getStatus();

// Get data for the copy (album instance) we're editing
require_once('AlbumInstanceQuery.php');
$aiQuery = new AlbumInstanceQuery($mysqli);
$context['ainstance'] = $aiQuery->getAlbumInstance($_REQUEST['ainstance_id']);

require_once('TrackQuery.php');
$trackQuery = new TrackQuery($mysqli);

if (isset($_REQUEST['action'])) {

	switch($_REQUEST['action']) {

		case "checkOut":

			require_once('UserQuery.php');
			$userQuery = new UserQuery($mysqli);

			// Check that the user has a valid status
			$user = $userQuery->getUser($_REQUEST['user_id']);

			if($user['ustatus_id'] == 3) {
				$alert = "<div class='alert alert-danger' role='alert'>User has an unpaid fine of $". $user['fine'] . ".<br>Fine must be paid before check-out priveleges are restored.</div>";
				break;
			}

			if($user['ustatus_id'] == 2) {
				$alert = "<div class='alert alert-danger' role='alert'>User is disabled.  Check-out not permitted.</div>";
				break;
			}

			// Undergrads can borrow for 3 days.  Everyone else gets 7.
			if($user['utype_id'] == 2) {
				$lendingPeriod = 3;
			} else {
				$lendingPeriod = 7;
			}

			$error = $albumQuery->checkOut($_REQUEST['ainstance_id'], $_REQUEST['user_id'], $lendingPeriod);
			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Check Out Successful</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Check Out Failed</div>";
			}



		break;
		case "deleteCopy":

			$error = $aiQuery->deleteAlbumInstance($_REQUEST['ainstance_id']);

			if(!$error) {
				$alert = "<div class='alert alert-success' role='alert'>Copy Deleted Successfully</div>";
			} else {
				$alert = "<div class='alert alert-danger' role='alert'>Copy Delete Failed</div>";
			}
		break;
	}
}

// Populate the form with existing data
$result = $albumQuery->getAlbum($context['ainstance']['album_id']);
$copy_result = $albumQuery->getCopies($context['ainstance']['album_id']);

$context['ainstance'] = $aiQuery->getAlbumInstance($_REQUEST['ainstance_id']);

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


// Since the template engine is brain-dead, we need to tell it which element to
// mark as selected in advance.
if(isset($context['ainstance']['astatus_id']) && isset($context['astatus'])) {
	for ($i = 0; $i < count($context['astatus']); $i++) {
		if($context['ainstance']['astatus_id'] == $context['astatus'][$i]['astatus_id']) {
				$context['astatus'][$i]['selected'] = true;
		}
	}
}

// Populate the variables to pass to the template
if (isset($alert)) {
	$context['alert'] = $alert;
}

echo $tpl->render($context);
?>
